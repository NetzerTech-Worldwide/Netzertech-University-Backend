<?php

namespace App\Http\Controllers\Api\V1\Admissions;

use App\Enums\AdmissionStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admissions\ApplicantRegisterRequest;
use App\Http\Resources\AdmissionsApplicationResource;
use App\Models\AdmissionsApplication;
use App\Models\Faculty;
use App\Models\JambRecord;
use App\Models\OLevelResult;
use App\Models\PostUtmeSlot;
use App\Models\Programme;
use App\Models\Student;
use App\Models\StudentProfile;
use App\Models\University;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AdmissionsController extends Controller
{
    use ApiResponse;

    /**
     * Return list of faculties and programmes for application forms.
     */
    public function getProgrammes(): JsonResponse
    {
        $faculties = Faculty::with('departments.programmes')->get();

        return $this->success($faculties, 'Programmes retrieved successfully.');
    }

    /**
     * Register a new applicant and create an initial application record.
     */
    public function register(ApplicantRegisterRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $tenantManager = app(\App\Services\Tenant\TenantManager::class);
            $university = $tenantManager->getTenant() ?? University::first();
            $universityId = $university?->id;
            $tenantCode = $university?->code ?? 'NVU';

            $user = User::create([
                'university_id' => $universityId,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'user_type' => UserType::APPLICANT->value,
                'password' => Hash::make($request->input('password')),
            ]);

            $user->assignRole('applicant');

            $year = date('Y');
            $appSerial = str_pad((string) (AdmissionsApplication::count() + 1), 5, '0', STR_PAD_LEFT);
            $appNumber = "APP/{$tenantCode}/{$year}/{$appSerial}";

            $application = AdmissionsApplication::create([
                'university_id' => $universityId,
                'user_id' => $user->id,
                'application_no' => $appNumber,
                'status' => AdmissionStatus::DRAFT->value,
                'steps_completed' => [
                    'personal' => false,
                    'jamb' => false,
                    'olevel' => false,
                    'programme' => false,
                    'documents' => false,
                ],
            ]);

            $token = $user->createToken('applicant-token')->plainTextToken;

            return $this->success([
                'token' => $token,
                'token_type' => 'Bearer',
                'application_no' => $appNumber,
                'application' => new AdmissionsApplicationResource($application),
            ], 'Applicant registered successfully.', Response::HTTP_CREATED);
        });
    }

    /**
     * Save step progress (personal, jamb, olevel, programme, documents).
     */
    public function saveStep(Request $request, string $step): JsonResponse
    {
        $user = $request->user();
        $application = AdmissionsApplication::where('user_id', $user->id)->firstOrFail();

        $stepsCompleted = $application->steps_completed ?? [];

        switch ($step) {
            case 'personal':
                $validated = $request->validate([
                    'dob' => ['nullable', 'date'],
                    'gender' => ['nullable', 'in:male,female,other'],
                    'state_of_origin' => ['nullable', 'string'],
                    'lga' => ['nullable', 'string'],
                    'nationality' => ['nullable', 'string'],
                    'address' => ['nullable', 'string'],
                    'city' => ['nullable', 'string'],
                    'state' => ['nullable', 'string'],
                    'father_name' => ['nullable', 'string'],
                    'father_phone' => ['nullable', 'string'],
                    'father_occupation' => ['nullable', 'string'],
                    'mother_name' => ['nullable', 'string'],
                    'mother_phone' => ['nullable', 'string'],
                    'mother_occupation' => ['nullable', 'string'],
                    'next_of_kin_name' => ['nullable', 'string'],
                    'next_of_kin_relationship' => ['nullable', 'string'],
                    'next_of_kin_phone' => ['nullable', 'string'],
                    'has_disability' => ['nullable', 'boolean'],
                    'religion' => ['nullable', 'string'],
                ]);

                // Store or update temporary profile attributes
                $stepsCompleted['personal'] = true;
                break;

            case 'jamb':
                $validated = $request->validate([
                    'reg_number' => ['required', 'string'],
                    'score' => ['required', 'integer', 'min:0', 'max:400'],
                    'year' => ['required', 'integer'],
                    'institution_chosen' => ['nullable', 'string'],
                    'course_chosen' => ['nullable', 'string'],
                ]);

                JambRecord::updateOrCreate(
                    ['application_id' => $application->id],
                    $validated
                );

                $stepsCompleted['jamb'] = true;
                break;

            case 'olevel':
                $validated = $request->validate([
                    'sittings' => ['required', 'array'],
                    'sittings.*.sitting_number' => ['required', 'integer', 'in:1,2'],
                    'sittings.*.exam_type' => ['required', 'string'],
                    'sittings.*.year' => ['required', 'integer'],
                    'sittings.*.exam_number' => ['required', 'string'],
                    'sittings.*.centre_number' => ['nullable', 'string'],
                    'sittings.*.subjects' => ['required', 'array'],
                ]);

                foreach ($validated['sittings'] as $sitting) {
                    OLevelResult::updateOrCreate(
                        [
                            'application_id' => $application->id,
                            'sitting_number' => $sitting['sitting_number'],
                        ],
                        $sitting
                    );
                }

                $stepsCompleted['olevel'] = true;
                break;

            case 'programme':
                $validated = $request->validate([
                    'first_choice_programme_id' => ['required', 'exists:programmes,id'],
                    'second_choice_programme_id' => ['nullable', 'exists:programmes,id'],
                ]);

                $application->update([
                    'first_choice_programme_id' => $validated['first_choice_programme_id'],
                    'second_choice_programme_id' => $validated['second_choice_programme_id'] ?? null,
                ]);

                $stepsCompleted['programme'] = true;
                break;

            case 'documents':
                $validated = $request->validate([
                    'documents' => ['required', 'array'],
                ]);

                $application->update([
                    'uploaded_documents' => $validated['documents'],
                ]);

                $stepsCompleted['documents'] = true;
                break;

            default:
                return $this->error("Invalid application step: {$step}", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $application->update(['steps_completed' => $stepsCompleted]);

        return $this->success(
            new AdmissionsApplicationResource($application->fresh(['firstChoiceProgramme.department.faculty', 'jambRecord', 'olevelResults'])),
            "Step '{$step}' saved successfully."
        );
    }

    /**
     * Submit completed application for screening review.
     */
    public function submit(Request $request): JsonResponse
    {
        $user = $request->user();
        $application = AdmissionsApplication::where('user_id', $user->id)->firstOrFail();

        $application->update([
            'status' => AdmissionStatus::UNDER_REVIEW->value,
            'submitted_at' => now(),
            'app_fee_paid' => true,
        ]);

        return $this->success(
            new AdmissionsApplicationResource($application),
            'Application submitted successfully for academic review.'
        );
    }

    /**
     * Check current application & screening status.
     */
    public function getStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        $application = AdmissionsApplication::where('user_id', $user->id)
            ->with(['firstChoiceProgramme.department.faculty', 'jambRecord', 'olevelResults', 'postUtmeSlot'])
            ->firstOrFail();

        return $this->success(
            new AdmissionsApplicationResource($application),
            'Application status retrieved.'
        );
    }

    /**
     * Book Post-UTME screening slot.
     */
    public function bookPostUtme(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'time' => ['required', 'string'],
            'venue' => ['required', 'string'],
        ]);

        $user = $request->user();
        $application = AdmissionsApplication::where('user_id', $user->id)->firstOrFail();

        $slot = PostUtmeSlot::updateOrCreate(
            ['application_id' => $application->id],
            [
                'exam_date' => $validated['date'],
                'exam_time' => $validated['time'],
                'venue' => $validated['venue'],
                'seat_number' => 'SEAT-' . rand(100, 999),
            ]
        );

        return $this->success($slot, 'Post-UTME screening session booked successfully.');
    }

    /**
     * Accept provisional admission offer and record acceptance payment.
     */
    public function acceptOffer(Request $request): JsonResponse
    {
        $user = $request->user();
        $application = AdmissionsApplication::where('user_id', $user->id)->firstOrFail();

        $application->update([
            'offer_accepted' => true,
            'acceptance_fee_paid' => true,
            'status' => AdmissionStatus::ADMITTED->value,
        ]);

        return $this->success(
            new AdmissionsApplicationResource($application),
            'Admission offer accepted and acceptance fee recorded.'
        );
    }

    /**
     * Matriculate student: Generate Matric Number, transition User to Student, and create initial profile.
     */
    public function matriculate(Request $request): JsonResponse
    {
        $user = $request->user();
        $application = AdmissionsApplication::where('user_id', $user->id)->firstOrFail();

        if (!$application->offer_accepted) {
            return $this->error('Cannot matriculate before accepting admission offer.', Response::HTTP_FORBIDDEN);
        }

        return DB::transaction(function () use ($user, $application) {
            $year = date('Y');
            $tenantManager = app(\App\Services\Tenant\TenantManager::class);
            $tenantCode = $tenantManager->getTenant()?->code ?? $application->university?->code ?? 'NVU';
            $universityId = $application->university_id ?? $tenantManager->tenantId();

            $programme = $application->firstChoiceProgramme;
            $deptCode = $programme?->department?->code ?? 'CSC';
            $serial = str_pad((string) (Student::count() + 1), 3, '0', STR_PAD_LEFT);
            $matricNumber = "{$tenantCode}/{$year}/{$deptCode}/{$serial}";

            // Update user type to student
            $user->update([
                'user_type' => UserType::STUDENT->value,
                'university_id' => $universityId,
            ]);
            $user->syncRoles(['student']);

            // Create Student Record
            $student = Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'university_id' => $universityId,
                    'matric_number' => $matricNumber,
                    'jamb_reg_no' => $application->jambRecord?->reg_number,
                    'faculty_id' => $programme->department->faculty_id,
                    'department_id' => $programme->department_id,
                    'programme_id' => $programme->id,
                    'level' => '100L',
                    'academic_session' => "{$year}/" . ($year + 1),
                    'current_semester' => 'first',
                    'entry_mode' => 'UTME',
                    'cgpa' => 0.00,
                    'standing' => 'Good Standing',
                    'digital_id_token' => hash('sha256', "{$matricNumber}-" . Str::random(16)),
                ]
            );

            // Create Profile Record
            StudentProfile::firstOrCreate(['student_id' => $student->id]);

            $application->update([
                'matric_number_issued' => $matricNumber,
                'profile_complete' => true,
            ]);

            return $this->success([
                'matric_number' => $matricNumber,
                'student' => $student->load(['faculty', 'department', 'programme', 'profile']),
            ], 'Matriculation successful! Student profile activated.');
        });
    }
}
