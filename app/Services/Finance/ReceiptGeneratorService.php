<?php

namespace App\Services\Finance;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptGeneratorService
{
    /**
     * Generate official receipt PDF binary.
     */
    public function generate(Payment $payment): string
    {
        $payment->load(['invoice.items', 'user.student', 'user.application', 'university']);

        $pdf = Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
            'invoice' => $payment->invoice,
            'user' => $payment->user,
            'university' => $payment->university,
        ])->setPaper('a4', 'portrait');

        return $pdf->output();
    }
}
