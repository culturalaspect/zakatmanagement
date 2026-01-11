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
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('is_active');
            $table->text('verification_remarks')->nullable()->after('verification_status');
            $table->text('rejection_reason')->nullable()->after('verification_remarks');
            $table->timestamp('verified_at')->nullable()->after('rejection_reason');
            $table->timestamp('rejected_at')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lzc_members', function (Blueprint $table) {
            $table->dropColumn(['verification_status', 'verification_remarks', 'rejection_reason', 'verified_at', 'rejected_at']);
        });
    }
};
