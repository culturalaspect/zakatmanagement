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
        $columnsToDrop = [];
        
        if (Schema::hasColumn('fund_allocations', 'installment_number')) {
            $columnsToDrop[] = 'installment_number';
        }
        if (Schema::hasColumn('fund_allocations', 'installment_amount')) {
            $columnsToDrop[] = 'installment_amount';
        }
        if (Schema::hasColumn('fund_allocations', 'release_date')) {
            $columnsToDrop[] = 'release_date';
        }
        
        if (!empty($columnsToDrop)) {
            Schema::table('fund_allocations', function (Blueprint $table) use ($columnsToDrop) {
                $table->dropColumn($columnsToDrop);
            });
        }
        
        // Add date column if it doesn't exist
        if (!Schema::hasColumn('fund_allocations', 'date')) {
            Schema::table('fund_allocations', function (Blueprint $table) {
                // Check if total_amount exists, if not add date after financial_year_id
                if (Schema::hasColumn('fund_allocations', 'total_amount')) {
                    $table->date('date')->after('total_amount');
                } else {
                    $table->date('date')->after('financial_year_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_allocations', function (Blueprint $table) {
            $table->integer('installment_number')->after('financial_year_id');
            $table->decimal('installment_amount', 20, 2)->after('installment_number');
        });
    }
};
