<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::table('donations', static function (Blueprint $table): void {
            $table->string('edonation_confirmation_id')->nullable();
            $table->string('edonation_confirmation_number')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('donations', static function (Blueprint $table): void {
            $table->dropColumn('edonation_confirmation_id');
            $table->dropColumn('edonation_confirmation_number');
        });
    }
};
