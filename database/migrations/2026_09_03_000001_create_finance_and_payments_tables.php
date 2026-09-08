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
        // ── Invoices ─────────────────────────────────────────────────────────
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('invoice_number', 50)->unique();
            $table->string('title');
            $table->string('fee_type', 40)->default('tuition'); // tuition, acceptance, hostel, faculty_levy, sug_levy, custom
            $table->string('academic_session', 20);
            $table->string('semester', 20)->nullable(); // first, second
            $table->decimal('base_amount', 12, 2)->default(0.00);
            $table->decimal('platform_fee', 12, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2)->default(0.00);
            $table->decimal('amount_paid', 12, 2)->default(0.00);
            $table->string('status', 20)->default('unpaid'); // unpaid, partially_paid, paid, cancelled
            $table->date('due_date')->nullable();
            $table->boolean('installment_allowed')->default(false);
            $table->json('installment_plan')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'user_id', 'status']);
            $table->index(['university_id', 'fee_type', 'academic_session']);
        });

        // ── Invoice Line Items ───────────────────────────────────────────────
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();

            $table->index(['university_id', 'invoice_id']);
        });

        // ── Payments & Gateway Splits ────────────────────────────────────────
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('transaction_reference', 80)->unique();
            $table->decimal('amount', 12, 2);
            $table->decimal('platform_fee', 12, 2)->default(0.00);
            $table->decimal('university_amount', 12, 2)->default(0.00);
            $table->string('gateway', 30)->default('paystack'); // paystack, remita, bank_transfer
            $table->string('gateway_reference', 100)->nullable();
            $table->string('status', 20)->default('pending'); // pending, successful, failed, abandoned
            $table->string('receipt_number', 50)->nullable()->unique();
            $table->string('split_code', 100)->nullable();
            $table->json('raw_webhook_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['university_id', 'transaction_reference']);
            $table->index(['university_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
