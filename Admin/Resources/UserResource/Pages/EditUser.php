<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\UserResource\Pages;

use Illuminate\Support\Arr;
use Kalnoy\Nestedset\QueryBuilder;
use Filament\Resources\Pages\EditRecord;
use Ziswapp\Domain\Foundation\Model\User;
use Modules\Inisiatif\Admin\Resources\UserResource;

final class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $active = Arr::pull($data, 'deactivated_at') === null;

        return \array_merge($data, \compact('active'));
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $active = Arr::pull($data, 'active');
        $password = Arr::pull($data, 'password');

        Arr::set($data, 'deactivated_at', $active ? null : now()->toDateTimeString());

        dd(
            $password ? \array_merge($data, [
                'password' => \bcrypt($password),
            ]) : $data
        );

        return $password ? \array_merge($data, [
            'password' => \bcrypt($password),
        ]) : $data;
    }

    protected function afterDelete(): void
    {
        $this->afterSave();
    }

    protected function afterSave(): void
    {
        /** @var QueryBuilder $builder */
        $builder = User::query();

        if ($builder->isBroken()) {
            $builder->fixTree();
        }
    }
}
