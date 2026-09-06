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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('staff_no', 30);
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $table->string('title', 20)->default('Dr.');
            $table->string('designation', 100);
            $table->string('rank', 50)->default('Lecturer');
            $table->string('initials', 10)->nullable();
            $table->string('color', 15)->default('#2E5FA3');
            $table->timestamps();

            $table->unique(['university_id', 'staff_no']);
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('matric_number', 30);
            $table->string('jamb_reg_no', 30)->nullable();
            $table->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('programme_id')->constrained('programmes')->cascadeOnDelete();
            $table->string('level', 10)->default('100L');
            $table->string('academic_session', 15)->default('2025/2026');
            $table->enum('current_semester', ['first', 'second'])->default('first');
            $table->enum('entry_mode', ['UTME', 'Direct Entry', 'Transfer', 'PG'])->default('UTME');
            $table->decimal('cgpa', 3, 2)->default(0.00);
            $table->string('standing', 50)->default('Good Standing');
            $table->string('digital_id_token', 64)->nullable()->unique();
            $table->timestamps();

            $table->unique(['university_id', 'matric_number']);
        });

        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('male');
            $table->string('state_of_origin', 50)->nullable();
            $table->string('lga', 50)->nullable();
            $table->string('nationality', 50)->default('Nigerian');
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            
            // Family & Next of Kin
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('next_of_kin_name')->nullable();
            $table->string('next_of_kin_relationship')->nullable();
            $table->string('next_of_kin_phone')->nullable();
            
            // Medical & Health
            $table->string('blood_group', 5)->nullable();
            $table->string('genotype', 5)->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->boolean('has_disability')->default(false);
            $table->string('religion', 30)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('students');
        Schema::dropIfExists('staff');
    }
};
