<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_zakat_committee_mohalla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_zakat_committee_id')->constrained('local_zakat_committees')->onDelete('cascade');
            $table->foreignId('mohalla_id')->constrained('mohallas')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['local_zakat_committee_id', 'mohalla_id'], 'lzc_mohalla_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_zakat_committee_mohalla');
    }
};
