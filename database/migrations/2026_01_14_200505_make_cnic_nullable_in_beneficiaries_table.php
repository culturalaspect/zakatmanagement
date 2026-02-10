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
        // Use DB facade to modify the column directly
        // MySQL allows multiple NULL values in a unique constraint, so we don't need to drop it
        \DB::statement('ALTER TABLE `beneficiaries` MODIFY `cnic` VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert CNIC to not nullable
        // First, set any NULL values to empty string
        \DB::statement('UPDATE `beneficiaries` SET `cnic` = "" WHERE `cnic` IS NULL');
        // Then make the column not nullable
        \DB::statement('ALTER TABLE `beneficiaries` MODIFY `cnic` VARCHAR(255) NOT NULL');
    }
};
