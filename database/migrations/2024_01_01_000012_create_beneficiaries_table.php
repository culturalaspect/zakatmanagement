<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phase_id')->constrained('phases')->onDelete('cascade');
            $table->foreignId('scheme_id')->constrained('schemes')->onDelete('cascade');
            $table->foreignId('scheme_category_id')->nullable()->constrained('scheme_categories')->onDelete('set null');
            $table->foreignId('local_zakat_committee_id')->constrained('local_zakat_committees')->onDelete('cascade');
            $table->string('cnic');
            $table->string('full_name');
            $table->string('father_husband_name');
            $table->string('mobile_number')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->decimal('amount', 15, 2);
            $table->boolean('requires_representative')->default(false);
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected', 'paid'])->default('pending');
            $table->text('rejection_remarks')->nullable();
            $table->text('district_remarks')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();
            
            // Ensure a beneficiary cannot be registered twice in the same phase and scheme
            $table->unique(['phase_id', 'scheme_id', 'cnic'], 'unique_beneficiary_phase_scheme');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
