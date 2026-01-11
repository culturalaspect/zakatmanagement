<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('union_councils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tehsil_id')->constrained('tehsils')->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['tehsil_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('union_councils');
    }
};
