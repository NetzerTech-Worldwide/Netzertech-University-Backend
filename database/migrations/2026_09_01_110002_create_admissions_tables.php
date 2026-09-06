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
        Schema::create('admissions_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('application_no', 30);
            $table->foreignId('first_choice_programme_id')->nullable()->constrained('programmes')->nullOnDelete();
            $table->foreignId('second_choice_programme_id')->nullable()->constrained('programmes')->nullOnDelete();
            
            $table->string('status', 30)->default('draft');
            $table->boolean('app_fee_paid')->default(false);
            $table->decimal('screening_score', 5, 2)->nullable();
            $table->boolean('offer_accepted')->default(false);
            $table->boolean('acceptance_fee_paid')->default(false);
            $table->boolean('profile_complete')->default(false);
            $table->boolean('documents_verified')->default(false);
            $table->string('matric_number_issued', 30)->nullable();
            
            $table->json('uploaded_documents')->nullable();
            $table->json('steps_completed')->nullable();
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(['university_id', 'application_no']);
        });

        Schema::create('jamb_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admissions_applications')->cascadeOnDelete();
            $table->string('reg_number', 30);
            $table->unsignedSmallInteger('score');
            $table->unsignedSmallInteger('year');
            $table->string('institution_chosen')->nullable();
            $table->string('course_chosen')->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });

        Schema::create('olevel_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admissions_applications')->cascadeOnDelete();
            $table->unsignedTinyInteger('sitting_number')->default(1);
            $table->string('exam_type', 20)->default('WAEC');
            $table->unsignedSmallInteger('year');
            $table->string('exam_number', 30);
            $table->string('centre_number', 30)->nullable();
            $table->json('subjects');
            $table->timestamps();
        });

        Schema::create('post_utme_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admissions_applications')->cascadeOnDelete();
            $table->date('exam_date');
            $table->string('exam_time', 20);
            $table->string('venue', 100);
            $table->string('seat_number', 20)->nullable();
            $table->boolean('is_attended')->default(false);
            $table->decimal('score_obtained', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_utme_slots');
        Schema::dropIfExists('olevel_results');
        Schema::dropIfExists('jamb_records');
        Schema::dropIfExists('admissions_applications');
    }
};
