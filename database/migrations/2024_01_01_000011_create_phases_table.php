<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_allocation_id')->constrained('fund_allocations')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->string('name');
            $table->integer('phase_number');
            $table->integer('max_beneficiaries');
            $table->decimal('max_amount', 20, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'open', 'closed', 'approved'])->default('draft');
            $table->timestamps();
            
            $table->unique(['fund_allocation_id', 'district_id', 'phase_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phases');
    }
};
