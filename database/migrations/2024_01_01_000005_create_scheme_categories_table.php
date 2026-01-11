<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheme_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('schemes')->onDelete('cascade');
            $table->string('name'); // e.g., Middle School, High School, College, University
            $table->decimal('amount', 15, 2); // Amount per beneficiary
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheme_categories');
    }
};
