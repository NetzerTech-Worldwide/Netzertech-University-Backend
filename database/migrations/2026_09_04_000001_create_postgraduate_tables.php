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
        // ── Postgraduate Research Candidate Profiles ─────────────────────────
        Schema::create('pg_student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('programme_type', 20)->default('Ph.D.'); // M.Sc., Ph.D., PGD
            $table->string('research_title')->nullable();
            $table->foreignId('primary_supervisor_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('co_supervisor_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('current_stage', 40)->default('coursework'); // coursework, proposal_defense, internal_defense, external_defense, senate_clearance, graduated
            $table->date('expected_graduation_date')->nullable();
            $table->integer('risk_score')->default(15); // 0 - 100
            $table->string('risk_level', 20)->default('low'); // low, medium, high
            $table->timestamps();

            $table->unique(['university_id', 'student_id']);
            $table->index(['university_id', 'current_stage']);
            $table->index(['university_id', 'risk_level']);
        });

        // ── Research Proposals ───────────────────────────────────────────────
        Schema::create('pg_proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('title');
            $table->text('abstract');
            $table->string('document_url')->nullable();
            $table->string('status', 30)->default('submitted'); // submitted, under_review, approved, revisions_required
            $table->text('reviewer_feedback')->nullable();
            $table->date('defense_date')->nullable();
            $table->decimal('defense_score', 5, 2)->nullable();
            $table->timestamps();

            $table->index(['university_id', 'student_id', 'status']);
        });

        // ── Thesis Chapter Milestones ────────────────────────────────────────
        Schema::create('pg_thesis_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->integer('chapter_number'); // 1, 2, 3, 4, 5, 6
            $table->string('title');
            $table->string('status', 30)->default('pending'); // pending, submitted, approved, revisions_needed
            $table->string('submission_url')->nullable();
            $table->text('supervisor_comments')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'chapter_number']);
            $table->index(['university_id', 'student_id', 'status']);
        });

        // ── Supervision Meeting Logs ─────────────────────────────────────────
        Schema::create('pg_supervision_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->date('meeting_date');
            $table->text('summary_notes');
            $table->text('next_deliverables');
            $table->string('status', 30)->default('pending_approval'); // pending_approval, confirmed
            $table->timestamps();

            $table->index(['university_id', 'student_id', 'meeting_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pg_supervision_logs');
        Schema::dropIfExists('pg_thesis_milestones');
        Schema::dropIfExists('pg_proposals');
        Schema::dropIfExists('pg_student_profiles');
    }
};
