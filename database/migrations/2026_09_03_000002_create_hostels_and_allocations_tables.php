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
        // ── Hostels ──────────────────────────────────────────────────────────
        Schema::create('hostels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 30);
            $table->string('gender', 15)->default('mixed'); // male, female, mixed
            $table->string('campus_location')->nullable();
            $table->foreignId('master_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->integer('capacity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_id', 'code']);
            $table->index(['university_id', 'gender']);
        });

        // ── Hostel Rooms ─────────────────────────────────────────────────────
        Schema::create('hostel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('hostel_id')->constrained('hostels')->cascadeOnDelete();
            $table->string('room_number', 20);
            $table->string('block_name', 20)->nullable();
            $table->integer('floor')->default(1);
            $table->string('room_type', 30)->default('quad'); // single, double, quad
            $table->integer('capacity')->default(4);
            $table->integer('allocated_count')->default(0);
            $table->decimal('fee_amount', 10, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['hostel_id', 'room_number']);
            $table->index(['university_id', 'hostel_id']);
        });

        // ── Bed Spaces ───────────────────────────────────────────────────────
        Schema::create('bed_spaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('hostel_room_id')->constrained('hostel_rooms')->cascadeOnDelete();
            $table->string('bed_label', 20); // Bed A, Bed B, Bed C, Bed D
            $table->string('status', 20)->default('available'); // available, reserved, occupied, maintenance
            $table->string('lock_token', 64)->nullable();
            $table->timestamp('locked_until')->nullable();
            $table->timestamps();

            $table->unique(['hostel_room_id', 'bed_label']);
            $table->index(['university_id', 'status']);
        });

        // ── Hostel Allocations ───────────────────────────────────────────────
        Schema::create('hostel_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('bed_space_id')->constrained('bed_spaces')->cascadeOnDelete();
            $table->string('academic_session', 20);
            $table->string('allocation_ref', 50)->unique();
            $table->string('status', 20)->default('allocated'); // allocated, confirmed, checked_in, vacated, revoked
            $table->boolean('rules_agreed')->default(false);
            $table->timestamp('rules_agreed_at')->nullable();
            $table->date('check_in_date')->nullable();
            $table->date('check_out_date')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'student_id', 'academic_session']);
            $table->index(['university_id', 'bed_space_id']);
        });

        // ── Hostel Maintenance Tickets ───────────────────────────────────────
        Schema::create('hostel_maintenance_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('hostel_room_id')->constrained('hostel_rooms')->cascadeOnDelete();
            $table->string('ticket_number', 50)->unique();
            $table->string('category', 30)->default('other'); // plumbing, electrical, carpentry, cleanliness, other
            $table->string('priority', 20)->default('medium'); // low, medium, high, emergency
            $table->text('description');
            $table->string('status', 20)->default('open'); // open, in_progress, resolved, closed
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by_staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->timestamps();

            $table->index(['university_id', 'hostel_room_id', 'status']);
            $table->index(['university_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_maintenance_tickets');
        Schema::dropIfExists('hostel_allocations');
        Schema::dropIfExists('bed_spaces');
        Schema::dropIfExists('hostel_rooms');
        Schema::dropIfExists('hostels');
    }
};
