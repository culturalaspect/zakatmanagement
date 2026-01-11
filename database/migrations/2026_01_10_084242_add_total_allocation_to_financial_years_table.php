<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('financial_years', function (Blueprint $table) {
            $table->decimal('total_allocation', 20, 2)->nullable()->after('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_years', function (Blueprint $table) {
            $table->dropColumn('total_allocation');
        });
    }
};
