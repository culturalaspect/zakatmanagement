<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('financial_year'); // e.g., 2025-26
            $table->decimal('total_amount', 20, 2);
            $table->integer('installment_number'); // 1, 2, 3, etc.
            $table->decimal('installment_amount', 20, 2);
            $table->date('release_date');
            $table->text('source')->nullable(); // Ministry of Poverty Alleviation and Social Safety Islamabad
            $table->enum('status', ['pending', 'allocated', 'disbursing', 'completed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_allocations');
    }
};
