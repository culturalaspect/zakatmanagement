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
        // Check if we need to migrate from fund_allocation_id to installment_id
        $hasFundAllocationId = Schema::hasColumn('phases', 'fund_allocation_id');
        $hasInstallmentId = Schema::hasColumn('phases', 'installment_id');
        
        if ($hasFundAllocationId && !$hasInstallmentId) {
            // Drop unique constraint first if it exists
            try {
                DB::statement('ALTER TABLE phases DROP INDEX phases_fund_allocation_id_district_id_phase_number_unique');
            } catch (\Exception $e) {
                // Constraint might not exist or have different name
            }
            
            // Drop foreign key if it exists
            try {
                DB::statement('ALTER TABLE phases DROP FOREIGN KEY phases_fund_allocation_id_foreign');
            } catch (\Exception $e) {
                // Foreign key might not exist or have different name
            }
            
            Schema::table('phases', function (Blueprint $table) {
                // Drop the old column
                $table->dropColumn('fund_allocation_id');
            });
        }
        
        // Add new column if it doesn't exist
        if (!Schema::hasColumn('phases', 'installment_id')) {
            Schema::table('phases', function (Blueprint $table) {
                // Add new column with new name
                $table->foreignId('installment_id')->after('id')->constrained('installments')->onDelete('cascade');
            });
        }
        
        // Add new unique constraint if it doesn't exist
        if (Schema::hasColumn('phases', 'installment_id')) {
            // Check if unique constraint already exists
            $constraints = DB::select("SHOW INDEX FROM phases WHERE Key_name = 'phases_installment_id_district_id_phase_number_unique'");
            if (empty($constraints)) {
                Schema::table('phases', function (Blueprint $table) {
                    $table->unique(['installment_id', 'district_id', 'phase_number'], 'phases_installment_id_district_id_phase_number_unique');
                });
            }
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phases', function (Blueprint $table) {
            // Drop new foreign key
            $table->dropForeign(['installment_id']);
            
            // Drop unique constraint
            try {
                $table->dropUnique(['installment_id', 'district_id', 'phase_number']);
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            
            // Drop the new column
            $table->dropColumn('installment_id');
        });
        
        Schema::table('phases', function (Blueprint $table) {
            // Add old column back
            $table->foreignId('fund_allocation_id')->after('id')->constrained('fund_allocations')->onDelete('cascade');
            
            // Add old unique constraint back
            $table->unique(['fund_allocation_id', 'district_id', 'phase_number']);
        });
    }
};
