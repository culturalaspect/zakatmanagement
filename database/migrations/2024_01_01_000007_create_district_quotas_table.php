<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('district_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_allocation_id')->constrained('fund_allocations')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->decimal('percentage', 5, 2);
            $table->integer('total_beneficiaries');
            $table->decimal('total_amount', 20, 2);
            $table->timestamps();
            
            $table->unique(['fund_allocation_id', 'district_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('district_quotas');
    }
};
