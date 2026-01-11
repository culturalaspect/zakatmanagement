<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiary_representatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->onDelete('cascade');
            $table->string('cnic');
            $table->string('full_name');
            $table->string('father_husband_name');
            $table->string('mobile_number')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('relationship'); // e.g., Father, Mother, Brother, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiary_representatives');
    }
};
