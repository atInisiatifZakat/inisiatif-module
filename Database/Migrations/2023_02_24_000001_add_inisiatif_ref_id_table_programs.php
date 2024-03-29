<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('programs', static function (Blueprint $table): void {
            $table->string('inisiatif_ref_id')->nullable();
        });

        Schema::table('funding_types', static function (Blueprint $table): void {
            $table->string('inisiatif_ref_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('programs', static function (Blueprint $table): void {
            $table->dropColumn('inisiatif_ref_id');
        });

        Schema::table('funding_types', static function (Blueprint $table): void {
            $table->dropColumn('inisiatif_ref_id');
        });
    }
};
