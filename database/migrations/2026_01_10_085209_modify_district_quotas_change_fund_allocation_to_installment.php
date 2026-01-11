<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop unique constraint first if it exists
        try {
            DB::statement('ALTER TABLE district_quotas DROP INDEX district_quotas_fund_allocation_id_district_id_unique');
        } catch (\Exception $e) {
            // Constraint might not exist or have different name
        }
        
        // Drop foreign key if it exists
        try {
            DB::statement('ALTER TABLE district_quotas DROP FOREIGN KEY district_quotas_fund_allocation_id_foreign');
        } catch (\Exception $e) {
            // Foreign key might not exist or have different name
        }
        
        // Only proceed if fund_allocation_id exists
        if (Schema::hasColumn('district_quotas', 'fund_allocation_id')) {
            Schema::table('district_quotas', function (Blueprint $table) {
                // Drop the old column
                $table->dropColumn('fund_allocation_id');
            });
        }
        
        // Add new column if it doesn't exist
        if (!Schema::hasColumn('district_quotas', 'installment_id')) {
            Schema::table('district_quotas', function (Blueprint $table) {
                // Add new column with new name
                $table->foreignId('installment_id')->after('id')->constrained('installments')->onDelete('cascade');
            });
        }
        
        // Add new unique constraint
        if (Schema::hasColumn('district_quotas', 'installment_id')) {
            Schema::table('district_quotas', function (Blueprint $table) {
                try {
                    $table->unique(['installment_id', 'district_id']);
                } catch (\Exception $e) {
                    // Constraint might already exist
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('district_quotas', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['installment_id']);
            
            // Drop the new column
            $table->dropColumn('installment_id');
        });
        
        Schema::table('district_quotas', function (Blueprint $table) {
            // Add old column back
            $table->foreignId('fund_allocation_id')->after('id')->constrained('fund_allocations')->onDelete('cascade');
        });
    }
};
