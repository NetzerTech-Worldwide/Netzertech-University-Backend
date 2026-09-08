<?php

namespace App\Http\Controllers\Api\V1\Identity;

use App\Http\Controllers\Controller;
use App\Services\Identity\DigitalIdCardService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class DigitalIdController extends Controller
{
    use ApiResponse;

    /**
     * Get the authenticated student's digital ID card with barcode token and QR payload.
     */
    public function getCard(Request $request, DigitalIdCardService $cardService): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required to view Digital ID.', HttpStatus::HTTP_NOT_FOUND);
        }

        $card = $cardService->getOrCreateCard($student);

        return $this->success([
            'card_number' => $card->card_number,
            'barcode_hash' => $card->barcode_hash,
            'qr_verification_url' => $card->qr_payload,
            'issue_date' => $card->issue_date->format('d M Y'),
            'expiry_date' => $card->expiry_date->format('M Y'),
            'is_active' => $card->is_active,
            'student' => [
                'name' => $student->user->name,
                'matric_number' => $student->matric_number,
                'faculty' => $student->faculty?->name,
                'department' => $student->department?->name,
                'programme' => $student->programme?->name,
                'level' => $student->level,
                'academic_session' => $student->academic_session,
                'blood_group' => $student->profile?->blood_group,
                'genotype' => $student->profile?->genotype,
                'photo_url' => $student->user->avatar_url,
            ],
            'university' => [
                'name' => $student->university->name,
                'code' => $student->university->code,
                'primary_color' => $student->university->primary_color,
            ],
        ], 'Digital ID card retrieved successfully.');
    }

    /**
     * Download printable official PDF ID card.
     */
    public function downloadPdf(Request $request, DigitalIdCardService $cardService): Response
    {
        $student = $request->user()->student;
        if (!$student) {
            abort(404, 'Student profile not found.');
        }

        $pdfBinary = $cardService->generatePdf($student);
        $safeMatric = str_replace('/', '_', $student->matric_number);

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"student_id_{$safeMatric}.pdf\"",
        ]);
    }

    /**
     * Public QR verification endpoint for campus gates and examination invigilators.
     */
    public function verifyPublic(string $hash, DigitalIdCardService $cardService): JsonResponse
    {
        $verification = $cardService->verifyHash($hash);

        if (!$verification['valid']) {
            return $this->error($verification['message'], HttpStatus::HTTP_NOT_FOUND);
        }

        return $this->success($verification, 'Student identity verified cryptographically.');
    }
}
