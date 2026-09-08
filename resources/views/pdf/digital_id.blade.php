<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital Student ID - {{ $student->matric_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .card-container {
            width: 320px;
            margin: 0 auto;
            border-radius: 16px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e5e7eb;
        }
        .card-header {
            background: linear-gradient(135deg, {{ $university->primary_color ?? '#2E5FA3' }} 0%, {{ $university->secondary_color ?? '#1A7A6E' }} 100%);
            color: #ffffff;
            padding: 16px;
            text-align: center;
        }
        .uni-title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }
        .card-type {
            font-size: 10px;
            font-weight: 600;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .card-body {
            padding: 16px;
            text-align: center;
        }
        .photo-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e5e7eb;
            color: {{ $university->primary_color ?? '#2E5FA3' }};
            font-size: 28px;
            font-weight: 700;
            line-height: 80px;
            margin: -20px auto 12px auto;
            border: 4px solid #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .student-name {
            font-size: 15px;
            font-weight: 800;
            color: #111827;
            margin: 0 0 2px 0;
        }
        .matric-no {
            font-size: 12px;
            font-weight: 700;
            color: {{ $university->primary_color ?? '#2E5FA3' }};
            margin: 0 0 14px 0;
        }
        .details-grid {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 10px;
            margin-bottom: 14px;
        }
        .details-grid td {
            padding: 4px 2px;
        }
        .lbl {
            color: #6b7280;
            font-weight: 600;
            width: 38%;
        }
        .val {
            color: #111827;
            font-weight: 700;
        }
        .barcode-section {
            background-color: #f9fafb;
            padding: 10px;
            border-top: 1px dashed #d1d5db;
            text-align: center;
        }
        .barcode-hash {
            font-family: monospace;
            font-size: 8px;
            color: #4b5563;
            letter-spacing: 1px;
            word-break: break-all;
            margin-top: 4px;
        }
        .verified-badge {
            display: inline-block;
            background-color: #ecfdf5;
            color: #059669;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 9999px;
            border: 1px solid #a7f3d0;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card-header">
            <h2 class="uni-title">{{ $university->name }}</h2>
            <p class="card-type">Official Student Identity Card</p>
        </div>
        <div class="card-body">
            <div class="photo-box">
                {{ substr($student->user->name, 0, 1) }}
            </div>
            <h3 class="student-name">{{ $student->user->name }}</h3>
            <p class="matric-no">{{ $student->matric_number }}</p>

            <table class="details-grid">
                <tr>
                    <td class="lbl">Programme:</td>
                    <td class="val">{{ $student->programme?->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Department:</td>
                    <td class="val">{{ $student->department?->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Level / Session:</td>
                    <td class="val">{{ $student->level }} ({{ $student->academic_session }})</td>
                </tr>
                <tr>
                    <td class="lbl">Blood / Genotype:</td>
                    <td class="val">{{ $student->profile?->blood_group ?? 'N/A' }} / {{ $student->profile?->genotype ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="lbl">Card ID:</td>
                    <td class="val">{{ $card->card_number }}</td>
                </tr>
                <tr>
                    <td class="lbl">Valid Until:</td>
                    <td class="val">{{ $card->expiry_date->format('M Y') }}</td>
                </tr>
            </table>

            <div class="verified-badge">✓ CRYPTOGRAPHICALLY VERIFIED</div>
        </div>
        <div class="barcode-section">
            <div style="font-size: 10px; font-weight: 800; letter-spacing: 2px; color: #111827;">
                ||| | || |||| | | ||| || |||| | |||
            </div>
            <div class="barcode-hash">{{ substr($card->barcode_hash, 0, 32) }}</div>
        </div>
    </div>
</body>
</html>
