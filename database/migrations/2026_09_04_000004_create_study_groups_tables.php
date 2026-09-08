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
        // ── Study Groups ─────────────────────────────────────────────────────
        Schema::create('study_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('creator_student_id')->constrained('students')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_members')->default(8);
            $table->string('meeting_schedule')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['university_id', 'course_id']);
        });

        // ── Study Group Members ──────────────────────────────────────────────
        Schema::create('study_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('study_group_id')->constrained('study_groups')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('role', 20)->default('member'); // lead, member
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['study_group_id', 'student_id']);
        });

        // ── Group Discussion Messages ────────────────────────────────────────
        Schema::create('study_group_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('study_group_id')->constrained('study_groups')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->text('message');
            $table->string('attachment_url')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'study_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_group_messages');
        Schema::dropIfExists('study_group_members');
        Schema::dropIfExists('study_groups');
    }
};
