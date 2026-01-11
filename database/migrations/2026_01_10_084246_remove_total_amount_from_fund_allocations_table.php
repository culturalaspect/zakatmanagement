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
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->decimal('total_amount', 20, 2)->after('financial_year_id');
        });
    }
};
