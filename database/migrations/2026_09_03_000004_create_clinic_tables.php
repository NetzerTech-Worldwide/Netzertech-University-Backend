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
        // ── Clinic Registrations & Hospital Bio-Data ─────────────────────────
        Schema::create('clinic_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('hospital_number', 50)->unique();
            $table->string('blood_group', 10);
            $table->string('genotype', 10);
            $table->text('allergies')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone', 30);
            $table->string('emergency_contact_relation', 50)->default('Parent/Guardian');
            $table->boolean('is_cleared')->default(true);
            $table->timestamp('registered_at')->nullable();
            $table->timestamps();

            $table->unique(['university_id', 'student_id']);
            $table->index(['university_id', 'hospital_number']);
        });

        // ── Clinic Consultations & Prescriptions ─────────────────────────────
        Schema::create('clinic_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('doctor_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->date('visit_date');
            $table->text('symptoms');
            $table->text('diagnosis')->nullable();
            $table->text('prescription')->nullable();
            $table->text('doctor_notes')->nullable();
            $table->string('status', 20)->default('scheduled'); // scheduled, completed, cancelled
            $table->timestamps();

            $table->index(['university_id', 'student_id', 'visit_date']);
            $table->index(['university_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_appointments');
        Schema::dropIfExists('clinic_registrations');
    }
};
