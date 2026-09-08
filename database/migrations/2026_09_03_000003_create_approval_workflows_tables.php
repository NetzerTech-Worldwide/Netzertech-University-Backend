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
        // ── Approval Requests ────────────────────────────────────────────────
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('request_ref', 50)->unique();
            $table->string('type', 40); // course_form_signing, transcript_request, deferral_of_exams, change_of_course, medical_leave, custom
            $table->string('title');
            $table->text('reason');
            $table->string('supporting_document_url')->nullable();
            $table->integer('total_levels')->default(2);
            $table->integer('current_level')->default(1);
            $table->string('status', 20)->default('pending'); // pending, in_review, approved, rejected
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'student_id', 'status']);
            $table->index(['university_id', 'type']);
        });

        // ── Approval Steps ───────────────────────────────────────────────────
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
            $table->foreignId('assigned_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('required_role', 50); // hod, dean, registrar, bursar, exam_officer, medical_officer, lecturer
            $table->integer('level_number')->default(1);
            $table->string('action', 20)->default('pending'); // pending, approved, rejected
            $table->text('comments')->nullable();
            $table->foreignId('acted_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'approval_request_id', 'level_number']);
            $table->index(['university_id', 'required_role', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('approval_requests');
    }
};
