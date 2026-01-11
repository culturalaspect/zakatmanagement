<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lzc_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_zakat_committee_id')->constrained('local_zakat_committees')->onDelete('cascade');
            $table->string('cnic')->unique();
            $table->string('full_name');
            $table->string('father_husband_name');
            $table->string('mobile_number')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lzc_members');
    }
};
