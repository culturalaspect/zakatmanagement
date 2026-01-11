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
            $table->decimal('jazzcash_amount', 15, 2)->nullable()->after('amount');
            $table->decimal('jazzcash_charges', 15, 2)->nullable()->after('jazzcash_amount');
            $table->decimal('jazzcash_total', 15, 2)->nullable()->after('jazzcash_charges');
            $table->string('jazzcash_status')->nullable()->after('jazzcash_total');
            $table->text('jazzcash_reason')->nullable()->after('jazzcash_status');
            $table->string('jazzcash_tid')->nullable()->after('jazzcash_reason');
            $table->timestamp('payment_received_at')->nullable()->after('jazzcash_tid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn([
                'jazzcash_amount',
                'jazzcash_charges',
                'jazzcash_total',
                'jazzcash_status',
                'jazzcash_reason',
                'jazzcash_tid',
                'payment_received_at',
            ]);
        });
    }
};
