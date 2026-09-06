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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('code', 20); // e.g. CSC 301
            $table->string('title');
            $table->unsignedTinyInteger('credit_units')->default(3);
            $table->string('level', 10)->default('100L');
            $table->enum('semester', ['first', 'second'])->default('first');
            $table->boolean('is_elective')->default(false);
            $table->foreignId('prerequisite_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_id', 'code']);
        });

        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('academic_session', 15)->default('2025/2026');
            $table->enum('semester', ['first', 'second'])->default('first');
            $table->unsignedTinyInteger('total_credits')->default(0);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'academic_session', 'semester']);
        });

        Schema::create('course_registration_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_registration_id')->constrained('course_registrations')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->enum('status', ['registered', 'dropped'])->default('registered');
            $table->timestamps();

            $table->unique(['course_registration_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_registration_items');
        Schema::dropIfExists('course_registrations');
        Schema::dropIfExists('courses');
    }
};
