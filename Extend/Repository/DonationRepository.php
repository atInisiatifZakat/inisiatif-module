<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Extend\Repository;

use DateTimeInterface;
use Flowframe\Trend\Trend;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Ziswapp\Domain\Foundation\Model\User;
use Ziswapp\Domain\Transaction\Repository;
use Ziswapp\Domain\Foundation\Model\Branch;
use Ziswapp\Domain\Foundation\Enum\BranchType;
use Ziswapp\Domain\Transaction\Model\Donation;
use Ziswapp\QueryBuilder\Filter\DateTimeFilter;
use Ziswapp\Domain\Transaction\Enum\DonationStatus;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ziswapp\Domain\Transaction\Builder\DonationQueryBuilder;
use Modules\Inisiatif\Enqueue\Contracts\HasConfirmationReference;

final class DonationRepository implements Repository\Contract\DonationRepository, HasConfirmationReference
{
    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountGroupByBranch(Branch $branch, DateTimeInterface $start, DateTimeInterface $end): Collection
    {
        if ($branch->isHeadOffice()) {
            return $this->fetchAmountGroupByBranchKc($start, $end);
        }

        return Donation::query()
            ->join('branches', 'branches.id', '=', 'donations.branch_id')
            ->whereStatus(DonationStatus::verified)
            ->whereBranches($branch->getSelfAndDescendants())
            ->whereBetween('transaction_at', [$start, $end])
            ->selectRaw('branches.id, branches.name as branch, sum(amount) as aggregate')
            ->groupBy('branches.id', 'branches.name')
            ->orderByRaw('sum(amount) DESC')
            ->get();
    }

    public function fetchAmountGroupByUser(Branch $branch, DateTimeInterface $start, DateTimeInterface $end): LengthAwarePaginator
    {
        return Donation::query()
            ->join('users', 'users.id', '=', 'donations.user_id')
            ->join('branches', 'branches.id', '=', 'donations.branch_id')
            ->whereBranches($branch->getSelfAndDescendants())
            ->whereStatus(DonationStatus::verified)
            ->whereBetween('transaction_at', [$start, $end])
            ->selectRaw('users.id, branches.name as branch, users.name as user, sum(amount) as aggregate')
            ->groupBy('users.id', 'branches.name', 'users.name')
            ->orderByRaw('sum(amount) desc')
            ->paginate();
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountGroupByType(Branch $branch, DateTimeInterface $start, DateTimeInterface $end, ?User $user = null): Collection
    {
        $branchIds = $branch->getSelfAndDescendants();
        $userIds = $user?->getCurrentAndDescendantIds();

        return Donation::query()
            ->selectRaw('type, sum(amount) as aggregate')
            ->whereBranches($branchIds)
            ->whereStatus(DonationStatus::verified)
            ->whereBetween('transaction_at', [$start, $end])
            ->when($userIds, fn (DonationQueryBuilder $builder) => $builder->whereUsers($userIds))
            ->groupBy('type')
            ->get();
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountVerified(Branch $branch, DateTimeInterface $start, DateTimeInterface $end, ?User $user = null): int|string
    {
        $branchIds = $branch->getSelfAndDescendants();
        $userIds = $user?->getCurrentAndDescendantIds();

        return Donation::query()
            ->whereBranches($branchIds)
            ->whereStatus(DonationStatus::verified)
            ->whereBetween('transaction_at', [$start, $end])
            ->when($userIds, fn (DonationQueryBuilder $builder) => $builder->whereUsers($userIds))
            ->sum('amount');
    }

    public function fetchUserAmountVerified(User $user, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): int|string
    {
        return Donation::query()
            ->whereStatus(DonationStatus::verified)
            ->when(
                $start !== null && $end !== null,
                fn(DonationQueryBuilder $builder) => $builder->whereBetween('transaction_at', [$start, $end])
            )
            ->whereUser($user)
            ->sum('amount');
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountNewAndPaid(Branch $branch, DateTimeInterface $start, DateTimeInterface $end, ?User $user = null): int|string
    {
        $branchIds = $branch->getSelfAndDescendants();
        $userIds = $user?->getCurrentAndDescendantIds();

        return Donation::query()
            ->whereBranches($branchIds)
            ->whereBetween('transaction_at', [$start, $end])
            ->whereStatusIn([DonationStatus::new, DonationStatus::paid])
            ->when($user, fn (DonationQueryBuilder $builder) => $builder->whereUsers($userIds))
            ->sum('amount');
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountPerPeriod(Branch $branch, DateTimeInterface $start, DateTimeInterface $end, ?User $user = null): Collection
    {
        $branchIds = $branch->getSelfAndDescendants();
        $userIds = $user?->getCurrentAndDescendantIds();

        $builder = Donation::query()
            ->whereBranches($branchIds)
            ->whereStatus(DonationStatus::verified)
            ->when($user, fn (DonationQueryBuilder $builder) => $builder->whereUsers($userIds));

        return Trend::query($builder)
            ->dateColumn('transaction_at')
            ->between($start, $end)
            ->perDay()
            ->sum('amount');
    }

    public function filterForVerify(Request $request): LengthAwarePaginator
    {
        /** @var string|null $keyword */
        $keyword = $request->query('q');

        $builder = Donation::query()
            ->withSearch($keyword)
            ->where(
                fn (Builder $builder) => $builder
                    ->where('is_inisiatif_verified', false)
                    ->orWhereNull('is_inisiatif_verified')
            )
            ->whereStatusIn([
                DonationStatus::new->value,
                DonationStatus::paid->value,
            ])
            ->oldest();

        return $this->makeQueryBuilder($builder, $request)->paginate();
    }

    public function filter(Request $request): LengthAwarePaginator
    {
        /** @var string|null $keyword */
        $keyword = $request->query('q');

        $builder = Donation::query()->withSearch($keyword)->latest();

        return $this->makeQueryBuilder($builder, $request)->paginate(
            $request->integer('limit', 15)
        );
    }

    public function makeQueryBuilder(Builder|Relation|string $subject, ?Request $request = null): QueryBuilder
    {
        return QueryBuilder::for($subject, $request)->with([
            'user' => fn (Relation $relation): Relation => $relation->select(['id', 'name']),
            'branch' => fn (Relation $relation): Relation => $relation->select(['id', 'name']),
            'donor' => fn (Relation $relation): Relation => $relation->select(['uuid', 'name']),
        ])->allowedFilters([
            AllowedFilter::exact('type', 'donations.type'),
            AllowedFilter::exact('status', 'donations.status'),
            AllowedFilter::exact('user', 'donations.user_id'),
            AllowedFilter::exact('branch', 'donations.branch_id'),
            AllowedFilter::exact('bank_account', 'donations.bank_account_id'),
            AllowedFilter::custom('transaction_date', new DateTimeFilter(), 'donations.transaction_at'),
            AllowedFilter::custom('verified_date', new DateTimeFilter(), 'donations.verified_at'),
        ]);
    }

    public function checkUsingReference(string $refId): bool
    {
        return Donation::query()->where('edonation_confirmation_id', $refId)->exists();
    }

    public function findUsingReference(string $refId): ?Donation
    {
        return Donation::query()->where('edonation_confirmation_id', $refId)->firstOr(static fn () => null);
    }

    private function fetchAmountGroupByBranchKc(DateTimeInterface $start, DateTimeInterface $end, bool $withZero = false): Collection
    {
        $donationKcQuery = Donation::query()
            ->selectRaw('branches.id as branch_id, sum(amount) as aggregate')
            ->leftJoin('branches', 'branches.id', '=', 'donations.branch_id')
            ->where('branches.type', BranchType::KC->value)
            ->whereStatus(DonationStatus::verified)
            ->whereBetween('transaction_at', [$start, $end])
            ->groupBy('branches.id')
            ->orderBy('branches.id');

        $donationKcpQuery = Donation::query()
            ->selectRaw('branches.parent_id as branch_id, sum(amount) as aggregate')
            ->leftJoin('branches', 'branches.id', '=', 'donations.branch_id')
            ->where('branches.type', BranchType::KCP->value)
            ->whereStatus(DonationStatus::verified)
            ->whereBetween('transaction_at', [$start, $end])
            ->groupBy('branches.parent_id')
            ->orderBy('branches.parent_id');

        return Branch::query()
            ->selectRaw('branches.id, branches.name as branch, sum(coalesce(kc.aggregate, 0) + coalesce(kcp.aggregate, 0)) as aggregate')
            ->leftJoinSub($donationKcQuery, 'kc', function (JoinClause $join): void {
                $join->on('branches.id', '=', 'kc.branch_id');
            })->leftJoinSub($donationKcpQuery, 'kcp', function (JoinClause $join): void {
                $join->on('branches.id', '=', 'kcp.branch_id');
            })
            ->when(! $withZero, fn (Builder $builder) => $builder->havingRaw('sum(coalesce(kc.aggregate, 0) + coalesce(kcp.aggregate, 0)) > 0'))
            ->groupBy('branches.id', 'branches.name')
            ->orderByRaw('sum(coalesce(kc.aggregate, 0) + coalesce(kcp.aggregate, 0)) DESC')
            ->get();
    }
}
