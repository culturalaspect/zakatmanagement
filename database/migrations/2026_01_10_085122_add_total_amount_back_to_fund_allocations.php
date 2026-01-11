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
            $table->decimal('total_amount', 20, 2)->after('financial_year_id');
        });
        
        // For MySQL, we need to drop and recreate the column
        // Check if release_date exists, if so, we'll handle it in the down migration
        // For now, just add date column (release_date will be dropped in remove_installment_fields migration)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->dropColumn('total_amount');
            $table->dropColumn('date');
        });
    }
};
