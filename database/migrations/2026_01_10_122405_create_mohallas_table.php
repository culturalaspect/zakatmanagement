<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mohallas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained('villages')->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['village_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mohallas');
    }
};
