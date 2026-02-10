<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run if the users table exists and has a role column
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            // Extend existing enum to include 'institution'
            // Adjust the list if your current enum has different values
            DB::statement("
                ALTER TABLE `users`
                MODIFY `role` ENUM('super_admin','administrator_hq','district_user','institution') NOT NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            // Revert to original enum without 'institution'
            DB::statement("
                ALTER TABLE `users`
                MODIFY `role` ENUM('super_admin','administrator_hq','district_user') NOT NULL
            ");
        }
    }
};

