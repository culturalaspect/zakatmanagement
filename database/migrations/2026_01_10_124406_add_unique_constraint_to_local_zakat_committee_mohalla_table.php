<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if unique constraint already exists by trying to add it
        // If it fails, it means it already exists, which is fine
        try {
            DB::statement('ALTER TABLE local_zakat_committee_mohalla ADD UNIQUE INDEX lzc_mohalla_unique (local_zakat_committee_id, mohalla_id)');
        } catch (\Exception $e) {
            // Constraint might already exist, which is fine
            if (strpos($e->getMessage(), 'Duplicate key name') === false && 
                strpos($e->getMessage(), 'already exists') === false) {
                throw $e;
            }
        }
    }

    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE local_zakat_committee_mohalla DROP INDEX lzc_mohalla_unique');
        } catch (\Exception $e) {
            // Index might not exist, which is fine
        }
    }
};
