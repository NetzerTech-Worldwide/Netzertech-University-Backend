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
        // ── Library Catalog Items ────────────────────────────────────────────
        Schema::create('library_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->string('isbn', 30)->nullable();
            $table->string('title');
            $table->string('author');
            $table->string('category', 40)->default('textbook'); // textbook, recommended, reference, journal
            $table->string('faculty')->nullable();
            $table->string('department')->nullable();
            $table->string('edition', 20)->nullable();
            $table->integer('year')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->string('shelf_location')->nullable();
            $table->boolean('is_digital')->default(false);
            $table->string('digital_download_url')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'category']);
            $table->index(['university_id', 'title']);
        });

        // ── Library Borrow / Loan Records ────────────────────────────────────
        Schema::create('library_borrow_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('library_item_id')->constrained('library_items')->cascadeOnDelete();
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->decimal('fine_amount', 10, 2)->default(0.00);
            $table->string('status', 20)->default('reserved'); // reserved, borrowed, returned, overdue
            $table->timestamps();

            $table->index(['university_id', 'student_id', 'status']);
            $table->index(['university_id', 'library_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_borrow_records');
        Schema::dropIfExists('library_items');
    }
};
