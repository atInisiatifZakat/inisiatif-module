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
use Ziswapp\Domain\Foundation\Model\User;
use Ziswapp\Domain\Transaction\Repository;
use Ziswapp\Domain\Foundation\Model\Branch;
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
        $branchIds = $branch->getSelfAndDescendants();

        return Donation::query()
            ->join('branches', 'branches.id', '=', 'donations.branch_id')
            ->whereStatus(DonationStatus::verified)
            ->whereBranches($branchIds)
            ->whereBetween('transaction_at', [$start, $end])
            ->selectRaw('branches.name as branch, sum(amount) as aggregate')
            ->groupBy('branches.name')
            ->orderBy('branches.name')
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
            ->when($userIds, fn(DonationQueryBuilder $builder) => $builder->whereUsers($userIds))
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
            ->when($user, fn(DonationQueryBuilder $builder) => $builder->whereUsers($userIds))
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
                fn(Builder $builder) => $builder
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
            'user' => fn(Relation $relation): Relation => $relation->select(['id', 'name']),
            'branch' => fn(Relation $relation): Relation => $relation->select(['id', 'name']),
            'donor' => fn(Relation $relation): Relation => $relation->select(['uuid', 'name']),
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
        return Donation::query()->where('edonation_confirmation_id', $refId)->firstOr(static fn() => null);
    }
}
