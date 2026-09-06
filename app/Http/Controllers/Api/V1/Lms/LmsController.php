<?php

namespace App\Http\Controllers\Api\V1\Lms;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LmsController extends Controller
{
    use ApiResponse;

    /**
     * Get lecture materials for a specific course.
     */
    public function getMaterials(int $courseId): JsonResponse
    {
        $materials = CourseMaterial::where('course_id', $courseId)
            ->with('uploader.user')
            ->orderBy('week_number')
            ->get();

        return $this->success($materials, 'Course materials retrieved.');
    }

    /**
     * Upload course material (Lecturer action).
     */
    public function uploadMaterial(Request $request, int $courseId): JsonResponse
    {
        $user = $request->user();
        $staff = $user->staff;

        if (!$staff) {
            return $this->error('Only academic staff can upload lecture materials.', Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'required|string',
            'file_type' => 'sometimes|string',
            'file_size_kb' => 'sometimes|integer',
            'week_number' => 'required|integer|min:1|max:16',
        ]);

        $course = Course::findOrFail($courseId);

        $material = CourseMaterial::create([
            'university_id' => $course->university_id,
            'course_id' => $course->id,
            'uploader_staff_id' => $staff->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'file_url' => $request->input('file_url'),
            'file_type' => $request->input('file_type', 'pdf'),
            'file_size_kb' => $request->input('file_size_kb', 1024),
            'week_number' => $request->input('week_number'),
        ]);

        return $this->success($material, 'Course material uploaded successfully.', Response::HTTP_CREATED);
    }

    /**
     * Get assignments for a course.
     */
    public function getAssignments(int $courseId): JsonResponse
    {
        $assignments = Assignment::where('course_id', $courseId)
            ->with(['creator.user', 'submissions'])
            ->orderByDesc('due_date')
            ->get();

        return $this->success($assignments, 'Assignments retrieved.');
    }

    /**
     * Create an assignment (Lecturer action).
     */
    public function createAssignment(Request $request, int $courseId): JsonResponse
    {
        $user = $request->user();
        $staff = $user->staff;

        if (!$staff) {
            return $this->error('Only academic staff can create assignments.', Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'required|string',
            'attachment_url' => 'nullable|string',
            'due_date' => 'required|date|after:now',
            'max_score' => 'sometimes|numeric|min:1|max:100',
        ]);

        $course = Course::findOrFail($courseId);

        $assignment = Assignment::create([
            'university_id' => $course->university_id,
            'course_id' => $course->id,
            'creator_staff_id' => $staff->id,
            'title' => $request->input('title'),
            'instructions' => $request->input('instructions'),
            'attachment_url' => $request->input('attachment_url'),
            'due_date' => $request->input('due_date'),
            'max_score' => $request->input('max_score', 30.00),
            'is_published' => true,
        ]);

        return $this->success($assignment, 'Assignment created successfully.', Response::HTTP_CREATED);
    }

    /**
     * Submit an assignment (Student action).
     */
    public function submitAssignment(Request $request, int $assignmentId): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return $this->error('Only matriculated students can submit assignments.', Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'submission_url' => 'required|string',
            'student_comment' => 'nullable|string',
        ]);

        $assignment = Assignment::findOrFail($assignmentId);

        $submission = AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $student->id,
            ],
            [
                'submission_url' => $request->input('submission_url'),
                'student_comment' => $request->input('student_comment'),
                'submitted_at' => now(),
            ]
        );

        return $this->success($submission, 'Assignment submitted successfully.', Response::HTTP_CREATED);
    }

    /**
     * Grade a student's submission (Lecturer action).
     */
    public function gradeSubmission(Request $request, int $submissionId): JsonResponse
    {
        $user = $request->user();
        $staff = $user->staff;

        if (!$staff) {
            return $this->error('Only academic staff can grade assignments.', Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'score' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        $submission = AssignmentSubmission::findOrFail($submissionId);

        $submission->update([
            'score' => $request->input('score'),
            'feedback' => $request->input('feedback'),
            'graded_by_staff_id' => $staff->id,
            'graded_at' => now(),
        ]);

        return $this->success($submission, 'Assignment submission graded successfully.');
    }
}
