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
        // Drop old unique constraint if it exists
        try {
            DB::statement('ALTER TABLE phases DROP INDEX phases_installment_id_district_id_phase_number_unique');
        } catch (\Exception $e) {
            // Constraint might not exist or have different name
        }
        
        // Add new unique constraint that includes scheme_id
        if (Schema::hasColumn('phases', 'installment_id') && 
            Schema::hasColumn('phases', 'district_id') && 
            Schema::hasColumn('phases', 'scheme_id') && 
            Schema::hasColumn('phases', 'phase_number')) {
            
            // Check if new constraint already exists
            $constraints = DB::select("SHOW INDEX FROM phases WHERE Key_name = 'phases_installment_id_district_id_scheme_id_phase_number_unique'");
            if (empty($constraints)) {
                Schema::table('phases', function (Blueprint $table) {
                    $table->unique(['installment_id', 'district_id', 'scheme_id', 'phase_number'], 'phases_installment_id_district_id_scheme_id_phase_number_unique');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop new unique constraint
        try {
            DB::statement('ALTER TABLE phases DROP INDEX phases_installment_id_district_id_scheme_id_phase_number_unique');
        } catch (\Exception $e) {
            // Constraint might not exist
        }
        
        // Restore old unique constraint
        if (Schema::hasColumn('phases', 'installment_id') && 
            Schema::hasColumn('phases', 'district_id') && 
            Schema::hasColumn('phases', 'phase_number')) {
            
            $constraints = DB::select("SHOW INDEX FROM phases WHERE Key_name = 'phases_installment_id_district_id_phase_number_unique'");
            if (empty($constraints)) {
                Schema::table('phases', function (Blueprint $table) {
                    $table->unique(['installment_id', 'district_id', 'phase_number'], 'phases_installment_id_district_id_phase_number_unique');
                });
            }
        }
    }
};
