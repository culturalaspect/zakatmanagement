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
        Schema::table('schemes', function (Blueprint $table) {
            $table->boolean('is_institutional')->default(false)->after('is_active');
            $table->boolean('allows_representative')->default(false)->after('is_institutional');
            $table->json('beneficiary_required_fields')->nullable()->after('allows_representative');
            $table->json('representative_required_fields')->nullable()->after('beneficiary_required_fields');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->dropColumn([
                'is_institutional',
                'allows_representative',
                'beneficiary_required_fields',
                'representative_required_fields'
            ]);
        });
    }
};
