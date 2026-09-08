<?php

namespace App\Services\Finance;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PaymentGatewayService
{
    /**
     * Initialize a payment transaction for an invoice with multi-gateway split routing.
     */
    public function initializePayment(Invoice $invoice, User $user, string $gateway = 'paystack', ?float $customAmount = null): array
    {
        if ($invoice->status === 'paid') {
            throw new InvalidArgumentException('This invoice is already fully paid.');
        }

        $outstanding = $invoice->outstanding_balance;
        $amountToPay = $customAmount ? min($customAmount, $outstanding) : $outstanding;

        if ($amountToPay <= 0) {
            throw new InvalidArgumentException('Payment amount must be greater than zero.');
        }

        $university = $invoice->university;
        $platformFee = (float) ($invoice->platform_fee > 0 ? $invoice->platform_fee : ($university->platform_fee_amount ?? 1500.00));
        
        // In fractional/installment payments, apportion or cap platform fee
        $platformFeeApplicable = min($platformFee, $amountToPay);
        $universityAmount = max(0.00, $amountToPay - $platformFeeApplicable);

        $reference = 'NZT-PAY-' . strtoupper(Str::random(8)) . '-' . time();
        $splitCode = $gateway === 'remita' 
            ? ($university->remita_merchant_id ?? 'MERCH_DEFAULT')
            : ($university->paystack_subaccount_code ?? 'ACCT_DEFAULT');

        $payment = Payment::create([
            'university_id' => $invoice->university_id,
            'invoice_id' => $invoice->id,
            'user_id' => $user->id,
            'transaction_reference' => $reference,
            'amount' => $amountToPay,
            'platform_fee' => $platformFeeApplicable,
            'university_amount' => $universityAmount,
            'gateway' => $gateway,
            'status' => 'pending',
            'split_code' => $splitCode,
        ]);

        return [
            'payment_id' => $payment->id,
            'transaction_reference' => $reference,
            'amount' => $amountToPay,
            'platform_convenience_fee' => $platformFeeApplicable,
            'university_payout' => $universityAmount,
            'split_account' => $splitCode,
            'gateway' => $gateway,
            'checkout_url' => "https://checkout.netzertech.com/pay/{$reference}",
            'invoice_number' => $invoice->invoice_number,
        ];
    }

    /**
     * Verify payment status and fulfill invoice upon successful payment.
     */
    public function verifyPayment(string $reference, ?string $gatewayRef = null, ?array $rawPayload = null): Payment
    {
        return DB::transaction(function () use ($reference, $gatewayRef, $rawPayload) {
            $payment = Payment::where('transaction_reference', $reference)->lockForUpdate()->firstOrFail();

            if ($payment->status === 'successful') {
                return $payment; // Idempotent response
            }

            $invoice = Invoice::where('id', $payment->invoice_id)->lockForUpdate()->firstOrFail();

            $receiptNumber = 'RCT-' . now()->format('Ymd') . '-' . str_pad((string) $payment->id, 5, '0', STR_PAD_LEFT);

            $payment->update([
                'status' => 'successful',
                'gateway_reference' => $gatewayRef ?? 'GW-' . strtoupper(Str::random(10)),
                'receipt_number' => $receiptNumber,
                'raw_webhook_payload' => $rawPayload,
                'paid_at' => now(),
            ]);

            // Update invoice balances
            $newAmountPaid = (float) $invoice->amount_paid + (float) $payment->amount;
            $isPaidInFull = $newAmountPaid >= (float) $invoice->total_amount;

            $invoice->update([
                'amount_paid' => $newAmountPaid,
                'status' => $isPaidInFull ? 'paid' : 'partially_paid',
            ]);

            if ($isPaidInFull) {
                $invoice->items()->update(['is_paid' => true]);
            }

            // Post-payment business fulfillment actions
            $this->fulfillLinkedServices($invoice, $payment);

            return $payment->fresh(['invoice', 'user']);
        });
    }

    /**
     * Handle incoming gateway webhook notifications idempotently.
     */
    public function handleWebhook(string $gateway, array $payload, ?string $signature = null): Payment
    {
        $reference = $payload['data']['reference'] ?? $payload['reference'] ?? null;

        if (!$reference) {
            throw new InvalidArgumentException('Missing transaction reference in webhook payload.');
        }

        $gatewayRef = $payload['data']['id'] ?? $payload['gateway_reference'] ?? null;

        return $this->verifyPayment($reference, (string) $gatewayRef, $payload);
    }

    /**
     * Fulfill services linked to invoice fee types.
     */
    protected function fulfillLinkedServices(Invoice $invoice, Payment $payment): void
    {
        if ($invoice->fee_type === 'acceptance') {
            $student = $invoice->user->student;
            $application = $invoice->user->application;
            if ($application) {
                $application->update(['acceptance_fee_paid' => true]);
            }
        }
    }
}
