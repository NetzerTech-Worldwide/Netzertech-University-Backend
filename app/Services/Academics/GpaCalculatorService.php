<?php

namespace App\Services\Academics;

use App\Models\CourseResult;
use App\Models\SemesterResult;
use App\Models\Student;

class GpaCalculatorService
{
    /**
     * Determine Grade and Grade Point based on NUC 5.0 scale.
     */
    public static function getGradeAndPoint(float $totalScore): array
    {
        if ($totalScore >= 70.0) {
            return ['grade' => 'A', 'grade_point' => 5.00];
        } elseif ($totalScore >= 60.0) {
            return ['grade' => 'B', 'grade_point' => 4.00];
        } elseif ($totalScore >= 50.0) {
            return ['grade' => 'C', 'grade_point' => 3.00];
        } elseif ($totalScore >= 45.0) {
            return ['grade' => 'D', 'grade_point' => 2.00];
        } else {
            return ['grade' => 'F', 'grade_point' => 0.00];
        }
    }

    /**
     * Determine class of degree / academic standing from CGPA.
     */
    public static function getStanding(float $cgpa): string
    {
        if ($cgpa >= 4.50) {
            return 'First Class Honours';
        } elseif ($cgpa >= 3.50) {
            return 'Second Class Honours (Upper Division)';
        } elseif ($cgpa >= 2.40) {
            return 'Second Class Honours (Lower Division)';
        } elseif ($cgpa >= 1.50) {
            return 'Third Class Honours';
        } else {
            return 'Academic Probation';
        }
    }

    /**
     * Compute and save semester GPA and cumulative CGPA for a student.
     */
    public function computeSemesterGpa(
        Student $student,
        string $session,
        string $semester,
        string $level = '100L'
    ): SemesterResult {
        // Fetch all course results for this student, session, and semester
        $courseResults = CourseResult::where('student_id', $student->id)
            ->where('academic_session', $session)
            ->where('semester', $semester)
            ->with('course')
            ->get();

        $semCreditsRegistered = 0;
        $semCreditsEarned = 0;
        $semGradePointsTotal = 0.00;

        foreach ($courseResults as $res) {
            $units = $res->course->credit_units;
            $semCreditsRegistered += $units;

            // Recalculate grade and points for consistency
            $score = (float) $res->total_score;
            $grading = self::getGradeAndPoint($score);
            $grade = $grading['grade'];
            $gp = $grading['grade_point'];
            $cp = round($units * $gp, 2);

            $res->update([
                'grade' => $grade,
                'grade_point' => $gp,
                'credit_points' => $cp,
            ]);

            if ($grade !== 'F') {
                $semCreditsEarned += $units;
            }

            $semGradePointsTotal += $cp;
        }

        $semGpa = $semCreditsRegistered > 0
            ? round($semGradePointsTotal / $semCreditsRegistered, 2)
            : 0.00;

        // Cumulative Computation across all previous semesters
        $allPriorResults = CourseResult::where('student_id', $student->id)
            ->where('status', '!=', 'draft')
            ->with('course')
            ->get();

        $cumCreditsRegistered = 0;
        $cumCreditsEarned = 0;
        $cumGradePointsTotal = 0.00;

        foreach ($allPriorResults as $res) {
            $units = $res->course->credit_units;
            $cumCreditsRegistered += $units;
            $cumGradePointsTotal += (float) $res->credit_points;

            if ($res->grade !== 'F') {
                $cumCreditsEarned += $units;
            }
        }

        $cumCgpa = $cumCreditsRegistered > 0
            ? round($cumGradePointsTotal / $cumCreditsRegistered, 2)
            : $semGpa;

        $standing = self::getStanding($cumCgpa);

        // Save or update SemesterResult
        $semesterResult = SemesterResult::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_session' => $session,
                'semester' => $semester,
            ],
            [
                'university_id' => $student->university_id,
                'level' => $level,
                'total_credits_registered' => $semCreditsRegistered,
                'total_credits_earned' => $semCreditsEarned,
                'total_grade_points' => $semGradePointsTotal,
                'gpa' => $semGpa,
                'cumulative_credits_registered' => $cumCreditsRegistered,
                'cumulative_credits_earned' => $cumCreditsEarned,
                'cumulative_grade_points' => $cumGradePointsTotal,
                'cgpa' => $cumCgpa,
                'standing' => $standing,
            ]
        );

        // Update Student's active overall CGPA and standing
        $student->update([
            'cgpa' => $cumCgpa,
            'standing' => $standing,
        ]);

        return $semesterResult;
    }
}
