<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['middle_school', 'high_school', 'college', 'university', 'madarsa', 'hospital']);
            $table->string('code')->unique()->nullable();
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('tehsil_id')->nullable()->constrained('tehsils')->onDelete('set null');
            $table->foreignId('union_council_id')->nullable()->constrained('union_councils')->onDelete('set null');
            $table->foreignId('village_id')->nullable()->constrained('villages')->onDelete('set null');
            $table->foreignId('mohalla_id')->nullable()->constrained('mohallas')->onDelete('set null');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('principal_director_name')->nullable();
            $table->string('registration_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
