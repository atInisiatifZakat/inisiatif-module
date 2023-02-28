<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Admin\Resources\UserResource\Pages;

use Illuminate\Support\Arr;
use Kalnoy\Nestedset\QueryBuilder;
use Ziswapp\Domain\Foundation\Model\User;
use Filament\Resources\Pages\CreateRecord;
use Modules\Inisiatif\Admin\Resources\UserResource;

final class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $active = Arr::pull($data, 'active');
        $password = Arr::pull($data, 'password');

        return \array_merge($data, [
            'password' => \bcrypt($password),
            'deactivated_at' => $active ? null : now()->toDateTimeString(),
        ]);
    }

    protected function afterCreate(): void
    {
        /** @var QueryBuilder $builder */
        $builder = User::query();

        if ($builder->isBroken()) {
            $builder->fixTree();
        }
    }
}
