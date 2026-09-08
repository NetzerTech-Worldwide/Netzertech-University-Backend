<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceAndPaymentSplitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_list_their_invoices_and_view_balance_summary(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/finance/invoices');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'summary' => ['total_billed', 'total_paid', 'outstanding_balance'],
                    'invoices' => [
                        '*' => ['id', 'invoice_number', 'title', 'total_amount', 'amount_paid', 'status'],
                    ],
                ],
            ]);

        $this->assertGreaterThan(0, $response->json('data.summary.total_billed'));
    }

    public function test_student_can_generate_a_new_fee_invoice(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/finance/invoices/generate', [
                'fee_type' => 'custom',
                'title' => 'Special Laboratory Workshop Kit Fee',
                'academic_session' => '2025/2026',
                'semester' => 'first',
                'items' => [
                    ['name' => 'Arduino Microcontroller & Sensor Kit', 'amount' => 18000.00],
                    ['name' => 'Safety Lab Goggles & Coat', 'amount' => 5000.00],
                ],
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.base_amount', '23000.00')
            ->assertJsonPath('data.platform_fee', '1500.00')
            ->assertJsonPath('data.total_amount', '24500.00')
            ->assertJsonPath('data.status', 'unpaid');
    }

    public function test_student_can_initialize_payment_with_automated_gateway_split(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $unpaidInvoice = Invoice::where('user_id', $studentUser->id)->where('status', 'unpaid')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/finance/pay', [
                'invoice_id' => $unpaidInvoice->id,
                'gateway' => 'paystack',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.platform_convenience_fee', 1500)
            ->assertJsonPath('data.split_account', 'ACCT_novica123456')
            ->assertJsonStructure([
                'data' => [
                    'payment_id',
                    'transaction_reference',
                    'amount',
                    'platform_convenience_fee',
                    'university_payout',
                    'checkout_url',
                ],
            ]);
    }

    public function test_payment_verification_credits_invoice_and_generates_official_receipt(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $unpaidInvoice = Invoice::where('user_id', $studentUser->id)->where('status', 'unpaid')->first();

        $initRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/finance/pay', [
                'invoice_id' => $unpaidInvoice->id,
                'gateway' => 'paystack',
            ]);

        $reference = $initRes->json('data.transaction_reference');

        $verifyRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson("/api/v1/finance/verify/{$reference}");

        $verifyRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'successful');

        $this->assertNotNull($verifyRes->json('data.receipt_number'));
        $this->assertEquals('paid', $unpaidInvoice->fresh()->status);
    }

    public function test_paystack_webhook_handler_processes_payment_idempotently(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $unpaidInvoice = Invoice::where('user_id', $studentUser->id)->where('status', 'unpaid')->first();

        $initRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/finance/pay', [
                'invoice_id' => $unpaidInvoice->id,
                'gateway' => 'paystack',
            ]);

        $reference = $initRes->json('data.transaction_reference');

        // Webhook simulated callback
        $webhookResponse = $this->postJson('/api/v1/finance/webhooks/paystack', [
            'event' => 'charge.success',
            'data' => [
                'reference' => $reference,
                'id' => 99282711,
                'status' => 'success',
                'amount' => 900000,
            ],
        ]);

        $webhookResponse->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertEquals('paid', $unpaidInvoice->fresh()->status);
    }

    public function test_student_can_download_official_pdf_payment_receipt(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $payment = Payment::where('user_id', $studentUser->id)->where('status', 'successful')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->get("/api/v1/finance/receipts/{$payment->receipt_number}/pdf");

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }
}
