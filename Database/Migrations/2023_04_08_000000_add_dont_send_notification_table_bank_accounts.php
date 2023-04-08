<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('bank_accounts', static function (Blueprint $table): void {
            $table->boolean('do_not_send_notification')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', static function (Blueprint $table): void {
            $table->dropColumn('do_not_send_notification');
        });
    }
};
