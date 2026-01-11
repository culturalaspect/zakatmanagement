<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->boolean('is_lump_sum')->default(false)->after('minimum_age');
        });
    }

    public function down(): void
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->dropColumn('is_lump_sum');
        });
    }
};
