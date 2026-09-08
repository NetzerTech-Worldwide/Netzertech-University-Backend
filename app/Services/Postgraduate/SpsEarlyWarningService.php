<?php

namespace App\Services\Postgraduate;

use App\Models\Invoice;
use App\Models\PgProposal;
use App\Models\PgStudentProfile;
use App\Models\PgSupervisionLog;
use App\Models\PgThesisMilestone;
use App\Models\Student;

class SpsEarlyWarningService
{
    /**
     * Compute and persist multidimensional risk score for an SPS candidate.
     */
    public function computeRisk(Student $student): array
    {
        $profile = PgStudentProfile::firstOrCreate(
            ['student_id' => $student->id],
            [
                'university_id' => $student->university_id,
                'programme_type' => 'Ph.D.',
                'current_stage' => 'coursework',
                'risk_score' => 20,
                'risk_level' => 'low',
            ]
        );

        // 1. Academic & Thesis Chapter Milestone Progress (Max 40 points)
        $approvedMilestones = PgThesisMilestone::where('student_id', $student->id)
            ->where('status', 'approved')
            ->count();

        $academicRisk = match (true) {
            $approvedMilestones === 0 => 35,
            $approvedMilestones < 2 => 25,
            $approvedMilestones < 4 => 15,
            $approvedMilestones < 6 => 5,
            default => 0,
        };

        // 2. Supervision Meeting Cadence (Max 30 points)
        $latestMeeting = PgSupervisionLog::where('student_id', $student->id)
            ->where('status', 'confirmed')
            ->latest('meeting_date')
            ->first();

        if (!$latestMeeting) {
            $supervisionRisk = 30;
            $daysSinceLastMeeting = null;
        } else {
            $daysSinceLastMeeting = (int) now()->diffInDays($latestMeeting->meeting_date);
            $supervisionRisk = match (true) {
                $daysSinceLastMeeting > 90 => 30,
                $daysSinceLastMeeting > 60 => 20,
                $daysSinceLastMeeting > 30 => 10,
                default => 0,
            };
        }

        // 3. Proposal Defense Stage (Max 20 points)
        $proposal = PgProposal::where('student_id', $student->id)->latest()->first();
        if (!$proposal || $proposal->status !== 'approved') {
            $proposalRisk = 20;
        } else {
            $proposalRisk = 0;
        }

        // 4. Financial Status (Max 10 points)
        $hasUnpaidInvoices = Invoice::where('user_id', $student->user_id)
            ->where('status', 'unpaid')
            ->exists();
        $financialRisk = $hasUnpaidInvoices ? 10 : 0;

        // Aggregate composite risk score
        $compositeScore = min(100, max(5, $academicRisk + $supervisionRisk + $proposalRisk + $financialRisk));
        $riskLevel = match (true) {
            $compositeScore >= 70 => 'high',
            $compositeScore >= 40 => 'medium',
            default => 'low',
        };

        $profile->update([
            'risk_score' => $compositeScore,
            'risk_level' => $riskLevel,
        ]);

        return [
            'student_id' => $student->id,
            'matric_number' => $student->matric_number,
            'candidate_name' => $student->user->name,
            'research_title' => $profile->research_title ?? 'Untitled Research',
            'current_stage' => $profile->current_stage,
            'composite_risk_score' => $compositeScore,
            'risk_level' => $riskLevel,
            'breakdown' => [
                'academic_milestones' => [
                    'score' => $academicRisk,
                    'approved_chapters' => $approvedMilestones,
                ],
                'supervision_cadence' => [
                    'score' => $supervisionRisk,
                    'days_since_last_meeting' => $daysSinceLastMeeting,
                ],
                'proposal_defense' => [
                    'score' => $proposalRisk,
                    'is_approved' => $proposal && $proposal->status === 'approved',
                ],
                'financial_standing' => [
                    'score' => $financialRisk,
                    'has_unpaid_invoices' => $hasUnpaidInvoices,
                ],
            ],
        ];
    }

    /**
     * Get aggregate early warning analytics for the postgraduate school.
     */
    public function getAnalytics(): array
    {
        $candidates = PgStudentProfile::with(['student.user', 'student.programme', 'primarySupervisor.user'])
            ->get();

        $highRisk = $candidates->where('risk_level', 'high')->values();
        $mediumRisk = $candidates->where('risk_level', 'medium')->values();
        $lowRisk = $candidates->where('risk_level', 'low')->values();

        return [
            'total_candidates' => $candidates->count(),
            'high_risk_count' => $highRisk->count(),
            'medium_risk_count' => $mediumRisk->count(),
            'low_risk_count' => $lowRisk->count(),
            'candidates' => $candidates->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->student->user->name,
                    'matric_number' => $c->student->matric_number,
                    'programme' => $c->student->programme?->name ?? 'Ph.D.',
                    'research_title' => $c->research_title,
                    'supervisor' => $c->primarySupervisor?->title . ' ' . $c->primarySupervisor?->user?->name,
                    'stage' => $c->current_stage,
                    'risk_score' => $c->risk_score,
                    'risk_level' => $c->risk_level,
                ];
            }),
        ];
    }
}
