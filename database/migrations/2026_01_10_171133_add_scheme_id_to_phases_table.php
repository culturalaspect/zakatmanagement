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
        // Only add if it doesn't exist
        if (!Schema::hasColumn('phases', 'scheme_id')) {
            Schema::table('phases', function (Blueprint $table) {
                $table->foreignId('scheme_id')->nullable()->after('district_id')->constrained('schemes')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phases', function (Blueprint $table) {
            $table->dropForeign(['scheme_id']);
            $table->dropColumn('scheme_id');
        });
    }
};
