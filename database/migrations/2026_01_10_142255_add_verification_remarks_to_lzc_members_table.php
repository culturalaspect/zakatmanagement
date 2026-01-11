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
        Schema::table('lzc_members', function (Blueprint $table) {
            if (!Schema::hasColumn('lzc_members', 'verification_remarks')) {
                $table->text('verification_remarks')->nullable()->after('verification_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lzc_members', function (Blueprint $table) {
            if (Schema::hasColumn('lzc_members', 'verification_remarks')) {
                $table->dropColumn('verification_remarks');
            }
        });
    }
};
