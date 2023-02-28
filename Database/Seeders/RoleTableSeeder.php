<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

final class RoleTableSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->truncate();

        $this->makePartnerAdmin();
        $this->makeFinanceRole();
        $this->makeSalesRole();
        $this->makeCrmRole();
    }

    private function makeSalesRole(): void
    {
        /** @var Role $role */
        $role = Role::query()->forceCreate([
            'name' => 'sales',
        ]);

        $role->givePermissionTo(
            Permission::query()->whereIn('name', [
                'action.donation.cancel', 'action.donation.create',
                'action.donation.edit', 'action.donation.show',
                'action.donor.create', 'action.donor.show',

                'menu.dashboard.donation', 'menu.donation',
                'menu.donor', 'menu.export.donation',
            ])->get()
        );
    }

    private function makeCrmRole(): void
    {
        /** @var Role $role */
        $role = Role::query()->forceCreate([
            'name' => 'crm',
        ]);

        $role->givePermissionTo(
            Permission::query()->whereIn('name', [
                'action.donation.cancel', 'action.donation.create',
                'action.donation.edit', 'action.donation.show',
                'action.donor.create', 'action.donor.show',

                'menu.dashboard.donation', 'menu.donation',
                'menu.donor', 'menu.export.donation',
            ])->get()
        );
    }

    private function makeFinanceRole(): void
    {
        /** @var Role $role */
        $role = Role::query()->forceCreate([
            'name' => 'finance',
        ]);

        $role->givePermissionTo(
            Permission::query()->whereIn('name', [
                'action.donation.cancel', 'action.donation.create', 'action.donation.edit',
                'action.donation.show', 'action.donation.correction', 'action.donation.verified',

                'action.donor.create', 'action.donor.show',

                'action.withdrawal.cancel', 'action.withdrawal.create', 'action.withdrawal.show',
                'action.deposit.cancel', 'action.deposit.create', 'action.deposit.show',

                'menu.dashboard.donation', 'menu.deposit', 'menu.donation', 'menu.donation.verified',
                'menu.donor', 'menu.export.donation', 'menu.export.donation', 'menu.export.ledger',
                'menu.ledger', 'menu.withdrawal',
            ])->get()
        );
    }

    private function makePartnerAdmin(): void
    {
        /** @var Role $role */
        $role = Role::query()->forceCreate([
            'name' => 'admin.mitra',
        ]);

        $role->givePermissionTo(
            Permission::query()->whereNotIn('name', [
                'menu.permissions',
                'menu.roles',
                'menu.funding.category',
                'menu.funding.type',
                'menu.program',
                'menu.program.category',
                'menu.ledger.setting',
                'menu.branches',

                'action.deposit.cancel-verify',
                'action.withdrawal.cancel-verify',
                'action.deposit.verified',
                'action.withdrawal.verify',
            ])->get()
        );
    }
}
