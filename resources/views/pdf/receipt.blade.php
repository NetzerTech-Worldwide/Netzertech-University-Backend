<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Payment Receipt - {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 24px;
        }
        .header {
            border-bottom: 2px solid {{ $university->primary_color ?? '#2E5FA3' }};
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .university-name {
            font-size: 20px;
            font-weight: 800;
            color: {{ $university->primary_color ?? '#2E5FA3' }};
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }
        .university-sub {
            font-size: 11px;
            color: #6b7280;
            margin: 0;
        }
        .receipt-badge {
            background-color: {{ $university->primary_color ?? '#2E5FA3' }};
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
        }
        .meta-table td {
            vertical-align: top;
            padding: 4px 0;
        }
        .label {
            font-weight: 600;
            color: #4b5563;
            width: 140px;
        }
        .value {
            color: #111827;
            font-weight: 500;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: 700;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .text-right {
            text-align: right;
        }
        .total-box {
            width: 50%;
            float: right;
            margin-bottom: 30px;
        }
        .total-row {
            padding: 5px 0;
            display: flex;
            justify-content: space-between;
        }
        .grand-total {
            border-top: 2px solid #111827;
            border-bottom: 2px solid #111827;
            font-size: 14px;
            font-weight: 800;
            padding: 8px 0;
            color: #111827;
        }
        .stamp-box {
            border: 2px dashed #059669;
            color: #059669;
            padding: 12px 18px;
            border-radius: 8px;
            display: inline-block;
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 13px;
            margin-top: 20px;
            transform: rotate(-3deg);
        }
        .footer {
            clear: both;
            margin-top: 50px;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 class="university-name">{{ $university->name }}</h1>
                    <p class="university-sub">{{ $university->address ?? 'Main Campus, Portal Services' }} | {{ $university->contact_email }}</p>
                </td>
                <td style="text-align: right;">
                    <span class="receipt-badge">Payment Receipt</span>
                    <p style="margin: 6px 0 0 0; font-size: 12px; font-weight: 700; color: #111827;">{{ $payment->receipt_number }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta-table">
        <tr>
            <td class="label">Payer Name:</td>
            <td class="value">{{ $user->name }}</td>
            <td class="label">Payment Date:</td>
            <td class="value">{{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : now()->format('M d, Y h:i A') }}</td>
        </tr>
        <tr>
            <td class="label">Matric / App No:</td>
            <td class="value">{{ $user->student?->matric_number ?? $user->application?->application_no ?? 'N/A' }}</td>
            <td class="label">Payment Channel:</td>
            <td class="value">{{ strtoupper($payment->gateway) }} (Ref: {{ $payment->transaction_reference }})</td>
        </tr>
        <tr>
            <td class="label">Invoice Number:</td>
            <td class="value">{{ $invoice->invoice_number }}</td>
            <td class="label">Session / Semester:</td>
            <td class="value">{{ $invoice->academic_session }} ({{ ucfirst($invoice->semester ?? 'Full Session') }})</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Category</th>
                <th class="text-right">Amount (₦)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ ucfirst($invoice->fee_type) }}</td>
                    <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ $invoice->title }}</td>
                    <td>{{ ucfirst($invoice->fee_type) }}</td>
                    <td class="text-right">{{ number_format($invoice->base_amount, 2) }}</td>
                </tr>
            @endforelse
            @if($payment->platform_fee > 0)
                <tr>
                    <td>Technology Service & Convenience Fee</td>
                    <td>Platform Fee</td>
                    <td class="text-right">{{ number_format($payment->platform_fee, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="width: 100%;">
        <div style="float: left; width: 45%;">
            <div class="stamp-box">
                ✓ PAYMENT VERIFIED & SETTLED<br>
                <span style="font-size: 9px; font-weight: 500;">AUTH CODE: {{ substr(hash('sha256', $payment->transaction_reference), 0, 16) }}</span>
            </div>
        </div>
        <div class="total-box">
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 4px 0; color: #4b5563;">Subtotal:</td>
                    <td style="text-align: right; font-weight: 600;">₦{{ number_format($payment->university_amount, 2) }}</td>
                </tr>
                <tr>
                    <td style="padding: 4px 0; color: #4b5563;">Convenience Fee:</td>
                    <td style="text-align: right; font-weight: 600;">₦{{ number_format($payment->platform_fee, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td style="padding: 8px 0;">Total Paid:</td>
                    <td style="text-align: right; color: {{ $university->primary_color ?? '#2E5FA3' }};">₦{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>This is a computer-generated official receipt for Netzertech University Portal. No physical signature is required.</p>
        <p>Verification Portal: https://verify.netzertech.com/receipt/{{ $payment->receipt_number }}</p>
    </div>
</body>
</html>
