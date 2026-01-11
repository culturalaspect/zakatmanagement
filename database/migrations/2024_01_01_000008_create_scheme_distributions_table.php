<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheme_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_quota_id')->constrained('district_quotas')->onDelete('cascade');
            $table->foreignId('scheme_id')->constrained('schemes')->onDelete('cascade');
            $table->decimal('percentage', 5, 2);
            $table->decimal('amount', 20, 2);
            $table->integer('beneficiaries_count')->default(0);
            $table->timestamps();
            
            $table->unique(['district_quota_id', 'scheme_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheme_distributions');
    }
};
