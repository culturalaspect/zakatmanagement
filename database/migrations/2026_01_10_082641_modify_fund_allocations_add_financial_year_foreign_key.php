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
            // Add financial_year_id column
            $table->foreignId('financial_year_id')->nullable()->after('id')->constrained('financial_years')->onDelete('restrict');
            
            // Drop the old string column
            $table->dropColumn('financial_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_allocations', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['financial_year_id']);
            $table->dropColumn('financial_year_id');
            
            // Add back the string column
            $table->string('financial_year')->after('id');
        });
    }
};
