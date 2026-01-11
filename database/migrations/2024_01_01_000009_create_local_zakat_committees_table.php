<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_zakat_committees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->string('name');
            $table->text('area_coverage')->nullable();
            $table->date('formation_date');
            $table->date('tenure_end_date');
            $table->integer('tenure_years')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_zakat_committees');
    }
};
