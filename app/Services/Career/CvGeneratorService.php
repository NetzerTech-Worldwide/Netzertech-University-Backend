<?php

namespace App\Services\Career;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class CvGeneratorService
{
    /**
     * Generate official ATS-optimized CV PDF binary.
     */
    public function generate(Student $student): string
    {
        $student->load(['user', 'programme', 'department', 'faculty', 'profile', 'skills', 'projects', 'certifications', 'university']);

        $pdf = Pdf::loadView('pdf.cv', [
            'student' => $student,
            'skills' => $student->skills,
            'projects' => $student->projects,
            'certifications' => $student->certifications,
        ])->setPaper('a4', 'portrait');

        return $pdf->output();
    }
}
