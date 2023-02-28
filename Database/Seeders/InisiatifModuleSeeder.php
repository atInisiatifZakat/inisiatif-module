<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DatabaseSeeder;

final class InisiatifModuleSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DatabaseSeeder::class);
        $this->call(RoleTableSeeder::class);

        $this->call(BankTableSeeder::class);
    }
}
