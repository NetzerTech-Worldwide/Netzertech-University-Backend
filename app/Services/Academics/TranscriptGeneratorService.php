<?php

namespace App\Services\Academics;

use App\Models\CourseResult;
use App\Models\SemesterResult;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class TranscriptGeneratorService
{
    /**
     * Generate official student transcript PDF.
     */
    public function generate(Student $student): string
    {
        $student->load(['user', 'university', 'faculty', 'department', 'programme', 'profile']);

        $semesters = SemesterResult::where('student_id', $student->id)
            ->orderBy('academic_session')
            ->orderBy('semester')
            ->get();

        $resultsGrouped = [];

        foreach ($semesters as $sem) {
            $courses = CourseResult::where('student_id', $student->id)
                ->where('academic_session', $sem->academic_session)
                ->where('semester', $sem->semester)
                ->with('course')
                ->get();

            $resultsGrouped[] = [
                'session' => $sem->academic_session,
                'semester' => ucfirst($sem->semester) . ' Semester',
                'level' => $sem->level,
                'gpa' => $sem->gpa,
                'cgpa' => $sem->cgpa,
                'total_credits' => $sem->total_credits_registered,
                'credits_earned' => $sem->total_credits_earned,
                'courses' => $courses,
            ];
        }

        $pdf = Pdf::loadView('pdf.transcript', [
            'student' => $student,
            'university' => $student->university,
            'semesters' => $resultsGrouped,
            'generated_at' => now()->format('d M Y, h:i A'),
        ]);

        return $pdf->output();
    }
}
