<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Controller;
use App\Models\ClinicAppointment;
use App\Models\ClinicRegistration;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class ClinicController extends Controller
{
    use ApiResponse;

    /**
     * Register student medical bio-data and issue university hospital card number.
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'blood_group' => 'required|string|max:10',
            'genotype' => 'required|string|max:10',
            'allergies' => 'nullable|string|max:1000',
            'chronic_conditions' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:30',
            'emergency_contact_relation' => 'nullable|string|max:50',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required for clinic registration.', HttpStatus::HTTP_FORBIDDEN);
        }

        $hospitalNo = 'NH-' . str_replace('/', '-', $student->matric_number);

        $registration = ClinicRegistration::updateOrCreate(
            ['student_id' => $student->id],
            [
                'university_id' => $student->university_id,
                'hospital_number' => $hospitalNo,
                'blood_group' => $request->input('blood_group'),
                'genotype' => $request->input('genotype'),
                'allergies' => $request->input('allergies'),
                'chronic_conditions' => $request->input('chronic_conditions'),
                'emergency_contact_name' => $request->input('emergency_contact_name'),
                'emergency_contact_phone' => $request->input('emergency_contact_phone'),
                'emergency_contact_relation' => $request->input('emergency_contact_relation', 'Parent/Guardian'),
                'is_cleared' => true,
                'registered_at' => now(),
            ]
        );

        // Also update student profile blood group and genotype for consistency
        if ($student->profile) {
            $student->profile->update([
                'blood_group' => $request->input('blood_group'),
                'genotype' => $request->input('genotype'),
                'allergies' => $request->input('allergies'),
                'medical_conditions' => $request->input('chronic_conditions'),
            ]);
        }

        return $this->success($registration, 'Medical registration completed successfully.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Get student digital clinic card & emergency records.
     */
    public function getCard(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile not found.', HttpStatus::HTTP_NOT_FOUND);
        }

        $card = ClinicRegistration::where('student_id', $student->id)->first();
        if (!$card) {
            return $this->error('Student is not yet registered at the university health clinic.', HttpStatus::HTTP_NOT_FOUND);
        }

        return $this->success([
            'idNumber' => $card->hospital_number,
            'studentName' => $student->user->name,
            'matricNumber' => $student->matric_number,
            'bloodGroup' => $card->blood_group,
            'genotype' => $card->genotype,
            'emergencyContact' => $card->emergency_contact_name,
            'emergencyPhone' => $card->emergency_contact_phone,
            'allergies' => $card->allergies ?? 'None reported',
            'chronicConditions' => $card->chronic_conditions ?? 'None',
            'dateIssued' => $card->registered_at ? $card->registered_at->format('M d, Y') : now()->format('M d, Y'),
            'isCleared' => $card->is_cleared,
        ], 'Clinic digital card retrieved.');
    }

    /**
     * List clinic consultations and visit history.
     */
    public function appointments(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = ClinicAppointment::with(['doctor.user', 'student.user']);

        if ($user->hasRole('student')) {
            $query->where('student_id', $user->student?->id);
        }

        $appointments = $query->latest('visit_date')->get();

        return $this->success($appointments, 'Clinic appointments retrieved.');
    }

    /**
     * Book a health center appointment.
     */
    public function bookAppointment(Request $request): JsonResponse
    {
        $request->validate([
            'visit_date' => 'required|date|after_or_equal:today',
            'symptoms' => 'required|string|max:1000',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $appointment = ClinicAppointment::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'visit_date' => $request->input('visit_date'),
            'symptoms' => $request->input('symptoms'),
            'status' => 'scheduled',
        ]);

        return $this->success($appointment, 'Appointment scheduled successfully.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Medical staff diagnosis and prescription entry.
     */
    public function diagnose(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'diagnosis' => 'required|string|max:1000',
            'prescription' => 'nullable|string|max:1000',
            'doctor_notes' => 'nullable|string|max:1000',
        ]);

        $appointment = ClinicAppointment::findOrFail($id);
        $staff = $request->user()->staff;

        $appointment->update([
            'doctor_staff_id' => $staff?->id,
            'diagnosis' => $request->input('diagnosis'),
            'prescription' => $request->input('prescription'),
            'doctor_notes' => $request->input('doctor_notes'),
            'status' => 'completed',
        ]);

        return $this->success($appointment, 'Consultation records and prescription saved.');
    }
}
