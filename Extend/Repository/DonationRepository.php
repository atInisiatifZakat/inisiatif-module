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

final class DonationRepository implements Repository\Contract\DonationRepository
{
    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountGroupByBranch(?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        return Donation::query()
            ->join('branches', 'branches.id', '=', 'donations.branch_id')
            ->whereStatus(DonationStatus::verified)
            ->when($start && $end, fn (Builder $builder) => $builder->whereBetween('transaction_at', [
                $start, $end,
            ]))
            ->selectRaw('branches.name as branch, sum(amount) as aggregate')
            ->groupBy('branches.name')
            ->orderBy('branches.name')
            ->get();
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountGroupByUser(Branch|int|null $branch = null, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        return Donation::query()
            ->join('users', 'users.id', '=', 'donations.user_id')
            ->when($branch, fn (DonationQueryBuilder $builder) => $builder->whereBranch($branch))
            ->whereStatus(DonationStatus::verified)
            ->when($start && $end, fn (Builder $builder) => $builder->whereBetween('transaction_at', [
                $start, $end,
            ]))
            ->selectRaw('users.name as user, sum(amount) as aggregate')
            ->groupBy('users.name')
            ->orderBy('users.name')
            ->get();
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountVerified(null|int|User $user = null, null|int|Branch $branch = null, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): int|string
    {
        return Donation::query()
            ->when($user, fn (DonationQueryBuilder $builder) => $builder->whereUser($user))
            ->when($branch, fn (DonationQueryBuilder $builder) => $builder->whereBranch($branch))
            ->whereStatus(DonationStatus::verified)
            ->when(
                $start && $end,
                fn (DonationQueryBuilder $builder) => $builder
                    ->whereBetween('transaction_at', [$start, $end])
            )->sum('amount');
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountPaid(null|int|User $user = null, null|int|Branch $branch = null, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): int|string
    {
        return Donation::query()
            ->when($user, fn (DonationQueryBuilder $builder) => $builder->whereUser($user))
            ->when($branch, fn (DonationQueryBuilder $builder) => $builder->whereBranch($branch))
            ->whereStatus(DonationStatus::paid)
            ->when($start && $end, fn (Builder $builder) => $builder->whereBetween('transaction_at', [
                $start, $end,
            ]))->sum('amount');
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountNewAndPaid(null|int|User $user = null, null|int|Branch $branch = null, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): int|string
    {
        return Donation::query()
            ->when($user, fn (DonationQueryBuilder $builder) => $builder->whereUser($user))
            ->when($branch, fn (DonationQueryBuilder $builder) => $builder->whereBranch($branch))
            ->whereStatusIn([
                DonationStatus::new,
                DonationStatus::paid,
            ])->when($start && $end, fn (Builder $builder) => $builder->whereBetween('transaction_at', [
                $start, $end,
            ]))->sum('amount');
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountPerStatus(null|int|User $user = null, null|int|Branch $branch = null, ?DateTimeInterface $start = null, ?DateTimeInterface $end = null): Collection
    {
        return Donation::query()
            ->when($user, fn (DonationQueryBuilder $builder) => $builder->whereUser($user))
            ->when($branch, fn (DonationQueryBuilder $builder) => $builder->whereBranch($branch))
            ->selectRaw('status, sum(amount) as aggregate')
            ->when($start && $end, fn (Builder $builder) => $builder->whereBetween('transaction_at', [
                $start, $end,
            ]))
            ->groupBy('status')
            ->orderBy('status')
            ->get();
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function fetchAmountPerPeriod(DateTimeInterface $start, DateTimeInterface $end, null|int|User $user = null, null|int|Branch $branch = null): Collection
    {
        $builder = Donation::query()
            ->when($user, fn (DonationQueryBuilder $builder) => $builder->whereUser($user))
            ->when($branch, fn (DonationQueryBuilder $builder) => $builder->whereBranch($branch))
            ->whereStatus(DonationStatus::verified);

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

        return $this->makeQueryBuilder($builder, $request)->paginate();
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
}
