<?php

namespace App\Http\Controllers\Api\V1\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Services\Finance\PaymentGatewayService;
use App\Services\Finance\ReceiptGeneratorService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class FinanceController extends Controller
{
    use ApiResponse;

    /**
     * List invoices for the authenticated student or all tenant invoices for bursary.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Invoice::with(['items', 'payments']);

        if ($user->hasRole(['student', 'applicant'])) {
            $query->where('user_id', $user->id);
        } elseif ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($feeType = $request->query('fee_type')) {
            $query->where('fee_type', $feeType);
        }

        if ($session = $request->query('academic_session')) {
            $query->where('academic_session', $session);
        }

        $invoices = $query->latest()->get();

        $totalBilled = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum('amount_paid');
        $totalOutstanding = max(0.00, $totalBilled - $totalPaid);

        return $this->success([
            'summary' => [
                'total_billed' => (float) $totalBilled,
                'total_paid' => (float) $totalPaid,
                'outstanding_balance' => (float) $totalOutstanding,
            ],
            'invoices' => $invoices,
        ], 'Invoices retrieved successfully.');
    }

    /**
     * Generate an invoice for tuition, faculty levy, or custom fee.
     */
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'fee_type' => 'required|string|in:tuition,acceptance,hostel,faculty_levy,sug_levy,custom',
            'title' => 'required|string|max:255',
            'academic_session' => 'required|string|max:20',
            'semester' => 'nullable|string|in:first,second',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'installment_allowed' => 'nullable|boolean',
        ]);

        $user = $request->user();
        $university = $user->university;

        $items = $request->input('items');
        $baseAmount = collect($items)->sum('amount');
        $platformFee = (float) ($university->platform_fee_amount ?? 1500.00);
        $totalAmount = $baseAmount + $platformFee;

        $invoiceNumber = 'INV-' . ($university->code ?? 'NVU') . '-' . now()->format('Y') . '-' . strtoupper(Str::random(5));

        $invoice = Invoice::create([
            'university_id' => $user->university_id,
            'user_id' => $user->id,
            'invoice_number' => $invoiceNumber,
            'title' => $request->input('title'),
            'fee_type' => $request->input('fee_type'),
            'academic_session' => $request->input('academic_session'),
            'semester' => $request->input('semester'),
            'base_amount' => $baseAmount,
            'platform_fee' => $platformFee,
            'total_amount' => $totalAmount,
            'amount_paid' => 0.00,
            'status' => 'unpaid',
            'due_date' => $request->input('due_date', now()->addDays(30)->toDateString()),
            'installment_allowed' => $request->boolean('installment_allowed', false),
        ]);

        foreach ($items as $item) {
            InvoiceItem::create([
                'university_id' => $user->university_id,
                'invoice_id' => $invoice->id,
                'name' => $item['name'],
                'amount' => $item['amount'],
                'is_paid' => false,
            ]);
        }

        return $this->success($invoice->load('items'), 'Invoice generated successfully.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Show a single invoice with line items and payments.
     */
    public function show(int $id): JsonResponse
    {
        $invoice = Invoice::with(['items', 'payments'])->findOrFail($id);
        return $this->success($invoice, 'Invoice retrieved successfully.');
    }

    /**
     * Initialize payment transaction with automated gateway split.
     */
    public function pay(Request $request, PaymentGatewayService $paymentService): JsonResponse
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'gateway' => 'nullable|string|in:paystack,remita,bank_transfer',
            'amount' => 'nullable|numeric|min:1',
        ]);

        $invoice = Invoice::findOrFail($request->input('invoice_id'));
        $user = $request->user();

        try {
            $paymentMeta = $paymentService->initializePayment(
                $invoice,
                $user,
                $request->input('gateway', 'paystack'),
                $request->input('amount') ? (float) $request->input('amount') : null
            );

            return $this->success($paymentMeta, 'Payment initiated successfully.', HttpStatus::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Verify payment status with payment gateway.
     */
    public function verify(string $reference, PaymentGatewayService $paymentService): JsonResponse
    {
        try {
            $payment = $paymentService->verifyPayment($reference);
            return $this->success($payment, 'Payment verified and credited successfully.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Webhook endpoint for Paystack callbacks.
     */
    public function webhookPaystack(Request $request, PaymentGatewayService $paymentService): JsonResponse
    {
        $signature = $request->header('x-paystack-signature');
        $payload = $request->all();

        try {
            $payment = $paymentService->handleWebhook('paystack', $payload, $signature);
            return response()->json(['status' => 'success', 'payment_id' => $payment->id]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Webhook endpoint for Remita callbacks.
     */
    public function webhookRemita(Request $request, PaymentGatewayService $paymentService): JsonResponse
    {
        $payload = $request->all();

        try {
            $payment = $paymentService->handleWebhook('remita', $payload);
            return response()->json(['status' => 'success', 'payment_id' => $payment->id]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Download official PDF receipt.
     */
    public function downloadReceipt(string $receiptNo, ReceiptGeneratorService $receiptService): Response
    {
        $payment = Payment::where('receipt_number', $receiptNo)->firstOrFail();
        $pdfBinary = $receiptService->generate($payment);

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"receipt-{$receiptNo}.pdf\"",
        ]);
    }
}
