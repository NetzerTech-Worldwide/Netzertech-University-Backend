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
        Schema::create('digital_id_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('card_number', 50)->unique();
            $table->string('barcode_hash', 100)->unique();
            $table->text('qr_payload')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['university_id', 'student_id']);
            $table->index(['university_id', 'barcode_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_id_cards');
    }
};
