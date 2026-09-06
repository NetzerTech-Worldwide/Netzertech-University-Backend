<?php

namespace App\Http\Controllers\Api\V1\Academics;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseResult;
use App\Models\SemesterResult;
use App\Models\Student;
use App\Services\Academics\GpaCalculatorService;
use App\Services\Academics\TranscriptGeneratorService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected GpaCalculatorService $gpaCalculator,
        protected TranscriptGeneratorService $transcriptGenerator
    ) {}

    /**
     * Upload or update course scores for a student (CA + Exam).
     */
    public function uploadScore(Request $request): JsonResponse
    {
        $user = $request->user();
        $staff = $user->staff;

        if (!$staff) {
            return $this->error('Only academic staff can record student scores.', Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'academic_session' => 'required|string',
            'semester' => 'required|in:first,second',
            'ca_score' => 'required|numeric|min:0|max:40',
            'exam_score' => 'required|numeric|min:0|max:70',
        ]);

        $student = Student::findOrFail($request->input('student_id'));
        $course = Course::findOrFail($request->input('course_id'));

        $ca = (float) $request->input('ca_score');
        $exam = (float) $request->input('exam_score');
        $total = $ca + $exam;

        $grading = GpaCalculatorService::getGradeAndPoint($total);
        $creditPoints = round($course->credit_units * $grading['grade_point'], 2);

        $result = CourseResult::updateOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'academic_session' => $request->input('academic_session'),
                'semester' => $request->input('semester'),
            ],
            [
                'university_id' => $student->university_id,
                'ca_score' => $ca,
                'exam_score' => $exam,
                'total_score' => $total,
                'grade' => $grading['grade'],
                'grade_point' => $grading['grade_point'],
                'credit_points' => $creditPoints,
                'status' => 'published',
                'uploaded_by_staff_id' => $staff->id,
            ]
        );

        // Automatically compute semester GPA and cumulative CGPA
        $semesterResult = $this->gpaCalculator->computeSemesterGpa(
            $student,
            $request->input('academic_session'),
            $request->input('semester'),
            $student->level
        );

        return $this->success([
            'course_result' => $result,
            'semester_result' => $semesterResult,
        ], 'Student result recorded and GPA recalculated successfully.');
    }

    /**
     * Get semester result slip for the authenticated student.
     */
    public function getSemesterResult(Request $request): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return $this->error('Student profile not found.', Response::HTTP_NOT_FOUND);
        }

        $session = $request->query('session', $student->academic_session);
        $semester = $request->query('semester', $student->current_semester);

        $semesterResult = SemesterResult::where('student_id', $student->id)
            ->where('academic_session', $session)
            ->where('semester', $semester)
            ->first();

        $courseResults = CourseResult::where('student_id', $student->id)
            ->where('academic_session', $session)
            ->where('semester', $semester)
            ->with('course')
            ->get();

        return $this->success([
            'session' => $session,
            'semester' => $semester,
            'summary' => $semesterResult,
            'courses' => $courseResults,
        ], 'Semester results retrieved.');
    }

    /**
     * Download official academic transcript PDF.
     */
    public function downloadTranscript(Request $request): HttpResponse
    {
        $user = $request->user();
        $student = $user->student;

        // If staff/admin is downloading for a specific student
        if (!$student && $studentId = $request->query('student_id')) {
            $student = Student::findOrFail($studentId);
        }

        if (!$student) {
            abort(Response::HTTP_NOT_FOUND, 'Student profile not found.');
        }

        $pdfOutput = $this->transcriptGenerator->generate($student);
        $filename = "Official_Transcript_{$student->matric_number}.pdf";

        return response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
