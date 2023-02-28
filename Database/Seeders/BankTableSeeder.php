<?php

declare(strict_types=1);

namespace Modules\Inisiatif\Database\Seeders;

use Illuminate\Database\Seeder;
use Ziswapp\Domain\Foundation\Model\Bank;

final class BankTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Bank Syariah Mandiri - Zakat', 'Bank Syariah Mandiri - Infak'] as $name) {
            Bank::query()->forceCreate([
                'name' => $name,
            ]);
        }
    }
}
