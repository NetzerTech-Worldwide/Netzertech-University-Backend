<?php

namespace App\Http\Controllers\Api\V1\Academics;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\CourseRegistrationItem;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    use ApiResponse;

    /**
     * List all courses available for the student's department/level.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Course::where('is_active', true)->with(['department', 'prerequisite']);

        if ($semester = $request->query('semester')) {
            $query->where('semester', $semester);
        }

        if ($level = $request->query('level')) {
            $query->where('level', $level);
        }

        if ($deptId = $request->query('department_id')) {
            $query->where('department_id', $deptId);
        }

        $courses = $query->orderBy('code')->get();

        return $this->success($courses, 'Courses retrieved successfully.');
    }

    /**
     * Submit semester course registration with credit limit validation.
     */
    public function register(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return $this->error('Only matriculated students can register courses.', Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'academic_session' => 'sometimes|string',
            'semester' => 'sometimes|in:first,second',
        ]);

        $session = $request->input('academic_session', $student->academic_session ?? '2025/2026');
        $semester = $request->input('semester', $student->current_semester ?? 'first');
        $courseIds = $request->input('course_ids');

        // Fetch selected courses
        $courses = Course::whereIn('id', $courseIds)->get();
        $totalCredits = $courses->sum('credit_units');

        // Validate Minimum and Maximum Credit Limits (NUC Standard: Min 15, Max 24)
        if ($totalCredits < 15) {
            return $this->error(
                "Total registered credits ({$totalCredits}) is below the minimum allowed of 15 credit units.",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($totalCredits > 24) {
            return $this->error(
                "Total registered credits ({$totalCredits}) exceeds the maximum limit of 24 credit units.",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return DB::transaction(function () use ($student, $session, $semester, $courses, $totalCredits) {
            $registration = CourseRegistration::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_session' => $session,
                    'semester' => $semester,
                ],
                [
                    'university_id' => $student->university_id,
                    'total_credits' => $totalCredits,
                    'status' => 'submitted',
                ]
            );

            // Sync registration items
            CourseRegistrationItem::where('course_registration_id', $registration->id)->delete();

            foreach ($courses as $course) {
                CourseRegistrationItem::create([
                    'course_registration_id' => $registration->id,
                    'course_id' => $course->id,
                    'status' => 'registered',
                ]);
            }

            $registration->load('items.course');

            return $this->success($registration, 'Course registration submitted successfully for HOD approval.', Response::HTTP_CREATED);
        });
    }

    /**
     * Get active course registration status for the logged-in student.
     */
    public function myRegistration(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return $this->error('Student profile not found.', Response::HTTP_NOT_FOUND);
        }

        $registration = CourseRegistration::where('student_id', $student->id)
            ->where('academic_session', $student->academic_session)
            ->where('semester', $student->current_semester)
            ->with(['items.course', 'approver.user'])
            ->first();

        if (!$registration) {
            return $this->error('No course registration record found for the current semester.', Response::HTTP_NOT_FOUND);
        }

        return $this->success($registration, 'Course registration details retrieved.');
    }

    /**
     * HOD approves or rejects a student's course registration.
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $staff = $user->staff;

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);

        $registration = CourseRegistration::findOrFail($id);

        $registration->update([
            'status' => $request->input('status'),
            'rejection_reason' => $request->input('rejection_reason'),
            'approved_by_staff_id' => $staff?->id,
            'approved_at' => now(),
        ]);

        return $this->success($registration, "Course registration has been {$request->input('status')}.");
    }
}
