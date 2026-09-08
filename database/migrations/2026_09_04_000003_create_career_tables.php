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
        // ── Student Skills ───────────────────────────────────────────────────
        Schema::create('student_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('name');
            $table->string('category', 40)->default('technical'); // technical, soft, language, tool
            $table->string('proficiency_level', 30)->default('intermediate'); // beginner, intermediate, advanced, expert
            $table->timestamps();

            $table->index(['university_id', 'student_id']);
        });

        // ── Portfolio Projects ───────────────────────────────────────────────
        Schema::create('student_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->json('technologies')->nullable();
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'student_id']);
        });

        // ── Student Certifications ───────────────────────────────────────────
        Schema::create('student_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('title');
            $table->string('issuer');
            $table->date('issue_date')->nullable();
            $table->string('credential_url')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'student_id']);
        });

        // ── Campus Jobs & Internships Board ──────────────────────────────────
        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->string('title');
            $table->string('company');
            $table->string('job_type', 30)->default('internship'); // internship, graduate, entry_level, part_time
            $table->string('location')->nullable();
            $table->string('salary_range')->nullable();
            $table->text('description')->nullable();
            $table->json('requirements')->nullable();
            $table->string('contact_email')->nullable();
            $table->date('deadline')->nullable();
            $table->string('apply_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['university_id', 'is_active', 'job_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_jobs');
        Schema::dropIfExists('student_certifications');
        Schema::dropIfExists('student_projects');
        Schema::dropIfExists('student_skills');
    }
};
