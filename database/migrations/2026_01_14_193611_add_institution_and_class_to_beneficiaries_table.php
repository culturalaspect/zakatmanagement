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
        Schema::table('beneficiaries', function (Blueprint $table) {
            // Make local_zakat_committee_id nullable since institutional schemes won't have it
            $table->foreignId('local_zakat_committee_id')->nullable()->change();
        });
        
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->foreignId('institution_id')->nullable()->after('local_zakat_committee_id')->constrained('institutions')->onDelete('set null');
            $table->string('class')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
            $table->dropColumn(['institution_id', 'class']);
            // Revert local_zakat_committee_id to not nullable
            $table->foreignId('local_zakat_committee_id')->nullable(false)->change();
        });
    }
};
