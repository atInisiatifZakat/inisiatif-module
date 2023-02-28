<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('branches', static function (Blueprint $table): void {
            $table->string('inisiatif_ref_id')->nullable();
        });

        Schema::table('foundations', static function (Blueprint $table): void {
            $table->string('inisiatif_ref_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('branches', static function (Blueprint $table): void {
            $table->dropColumn('inisiatif_ref_id');
        });

        Schema::table('foundations', static function (Blueprint $table): void {
            $table->dropColumn('inisiatif_ref_id');
        });
    }
};
