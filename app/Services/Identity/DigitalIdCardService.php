<?php

namespace App\Services\Identity;

use App\Models\DigitalIdCard;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class DigitalIdCardService
{
    /**
     * Get existing digital ID card or generate a cryptographic card for student.
     */
    public function getOrCreateCard(Student $student): DigitalIdCard
    {
        $existing = DigitalIdCard::where('student_id', $student->id)->first();
        if ($existing) {
            return $existing->load('student.user');
        }

        $uniCode = $student->university->code ?? 'NVU';
        $cardNumber = "{$uniCode}-ID-" . now()->format('Y') . '-' . str_pad((string) $student->id, 5, '0', STR_PAD_LEFT);
        
        // HMAC-SHA256 signature token
        $secretKey = config('app.key') ?? 'netzertech-secret-key-salt';
        $barcodeHash = hash_hmac('sha256', "{$student->matric_number}|{$student->id}|{$student->university_id}", $secretKey);

        return DigitalIdCard::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'card_number' => $cardNumber,
            'barcode_hash' => $barcodeHash,
            'qr_payload' => url("/api/v1/verify/id-card/{$barcodeHash}"),
            'issue_date' => now()->toDateString(),
            'expiry_date' => now()->addYears(4)->toDateString(),
            'is_active' => true,
        ]);
    }

    /**
     * Verify student ID card using public HMAC hash token.
     */
    public function verifyHash(string $hash): array
    {
        $card = DigitalIdCard::withoutGlobalScopes()
            ->where('barcode_hash', $hash)
            ->with(['student.user', 'student.programme', 'student.department', 'student.faculty', 'student.profile', 'student.university'])
            ->first();

        if (!$card || !$card->is_active) {
            return [
                'valid' => false,
                'message' => 'Invalid or expired student ID credentials.',
            ];
        }

        $student = $card->student;

        return [
            'valid' => true,
            'status' => 'ACTIVE & VERIFIED',
            'card' => [
                'card_number' => $card->card_number,
                'issue_date' => $card->issue_date->format('d M Y'),
                'expiry_date' => $card->expiry_date->format('M Y'),
                'barcode_hash' => $card->barcode_hash,
            ],
            'student' => [
                'name' => $student->user->name,
                'matric_number' => $student->matric_number,
                'institution' => $student->university->name,
                'faculty' => $student->faculty?->name,
                'department' => $student->department?->name,
                'programme' => $student->programme?->name,
                'level' => $student->level,
                'academic_session' => $student->academic_session,
                'standing' => $student->standing,
                'blood_group' => $student->profile?->blood_group,
                'genotype' => $student->profile?->genotype,
            ],
        ];
    }

    /**
     * Render printable PDF ID card binary.
     */
    public function generatePdf(Student $student): string
    {
        $card = $this->getOrCreateCard($student);
        $student->load(['user', 'university', 'faculty', 'department', 'programme', 'profile']);

        $pdf = Pdf::loadView('pdf.digital_id', [
            'student' => $student,
            'card' => $card,
            'university' => $student->university,
        ])->setPaper([0, 0, 340, 520], 'portrait');

        return $pdf->output();
    }
}
