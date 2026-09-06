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
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 10);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('dean_staff_id')->nullable();
            $table->timestamps();

            $table->unique(['university_id', 'code']);
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 10);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('hod_staff_id')->nullable();
            $table->timestamps();

            $table->unique(['university_id', 'code']);
        });

        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 10);
            $table->string('degree', 20)->default('B.Sc.'); // B.Sc., B.Eng., M.Sc., Ph.D.
            $table->unsignedTinyInteger('duration_years')->default(4);
            $table->timestamps();

            $table->unique(['university_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('faculties');
    }
};
