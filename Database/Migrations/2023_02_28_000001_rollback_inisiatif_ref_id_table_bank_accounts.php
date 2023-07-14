<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('bank_accounts', 'inisiatif_ref_id')) {
            Schema::dropColumns('bank_accounts', 'inisiatif_ref_id');
        }
    }
};
