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
        Schema::create('course_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('academic_session', 15)->default('2025/2026');
            $table->enum('semester', ['first', 'second'])->default('first');
            $table->decimal('ca_score', 5, 2)->default(0.00); // Continuous Assessment (max 30 or 40)
            $table->decimal('exam_score', 5, 2)->default(0.00); // Exam (max 70 or 60)
            $table->decimal('total_score', 5, 2)->default(0.00); // CA + Exam
            $table->enum('grade', ['A', 'B', 'C', 'D', 'F'])->default('F');
            $table->decimal('grade_point', 3, 2)->default(0.00); // 5.00, 4.00, 3.00, 2.00, 0.00
            $table->decimal('credit_points', 5, 2)->default(0.00); // credit_units * grade_point
            $table->enum('status', ['draft', 'submitted', 'approved_by_hod', 'published'])->default('draft');
            $table->foreignId('uploaded_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'course_id', 'academic_session', 'semester']);
        });

        Schema::create('semester_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('academic_session', 15)->default('2025/2026');
            $table->enum('semester', ['first', 'second'])->default('first');
            $table->string('level', 10)->default('100L');
            
            // Semester Stats
            $table->unsignedTinyInteger('total_credits_registered')->default(0);
            $table->unsignedTinyInteger('total_credits_earned')->default(0);
            $table->decimal('total_grade_points', 6, 2)->default(0.00);
            $table->decimal('gpa', 3, 2)->default(0.00);
            
            // Cumulative Stats
            $table->unsignedSmallInteger('cumulative_credits_registered')->default(0);
            $table->unsignedSmallInteger('cumulative_credits_earned')->default(0);
            $table->decimal('cumulative_grade_points', 7, 2)->default(0.00);
            $table->decimal('cgpa', 3, 2)->default(0.00);
            $table->string('standing', 50)->default('Good Standing'); // First Class, 2:1, 2:2, 3rd Class, Probation
            
            $table->timestamps();

            $table->unique(['student_id', 'academic_session', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_results');
        Schema::dropIfExists('course_results');
    }
};
