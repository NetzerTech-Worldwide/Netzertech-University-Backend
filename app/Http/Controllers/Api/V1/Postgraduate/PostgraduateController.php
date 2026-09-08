<?php

namespace App\Http\Controllers\Api\V1\Postgraduate;

use App\Http\Controllers\Controller;
use App\Models\PgProposal;
use App\Models\PgStudentProfile;
use App\Models\PgSupervisionLog;
use App\Models\PgThesisMilestone;
use App\Services\Postgraduate\SpsEarlyWarningService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class PostgraduateController extends Controller
{
    use ApiResponse;

    /**
     * Get candidate SPS research progress dashboard and telemetry.
     */
    public function dashboard(Request $request, SpsEarlyWarningService $spsService): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Postgraduate candidate profile required.', HttpStatus::HTTP_NOT_FOUND);
        }

        $profile = PgStudentProfile::where('student_id', $student->id)
            ->with(['primarySupervisor.user', 'coSupervisor.user'])
            ->first();

        $riskData = $spsService->computeRisk($student);
        $milestones = PgThesisMilestone::where('student_id', $student->id)->orderBy('chapter_number')->get();
        $proposal = PgProposal::where('student_id', $student->id)->latest()->first();

        return $this->success([
            'profile' => $profile,
            'candidate' => [
                'name' => $student->user->name,
                'matric_number' => $student->matric_number,
                'programme' => $student->programme?->name ?? 'Ph.D.',
                'department' => $student->department?->name,
            ],
            'research' => [
                'title' => $profile->research_title ?? 'Untitled Doctoral Research',
                'current_stage' => $profile->current_stage ?? 'coursework',
                'primary_supervisor' => $profile->primarySupervisor?->title . ' ' . $profile->primarySupervisor?->user?->name,
                'co_supervisor' => $profile->coSupervisor ? ($profile->coSupervisor->title . ' ' . $profile->coSupervisor->user?->name) : null,
                'expected_graduation' => $profile->expected_graduation_date?->format('M Y'),
            ],
            'proposal' => $proposal,
            'milestones_progress' => [
                'total_chapters' => $milestones->count() ?: 6,
                'approved_chapters' => $milestones->where('status', 'approved')->count(),
                'chapters' => $milestones,
            ],
            'risk_telemetry' => $riskData,
        ], 'Postgraduate candidate dashboard retrieved.');
    }

    /**
     * List research proposals.
     */
    public function getProposals(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = PgProposal::with('student.user');

        if ($user->hasRole('student')) {
            $query->where('student_id', $user->student?->id);
        }

        $proposals = $query->latest()->get();

        return $this->success($proposals, 'Research proposals retrieved.');
    }

    /**
     * Submit or update research proposal.
     */
    public function submitProposal(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|max:4000',
            'document_url' => 'nullable|url',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $proposal = PgProposal::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'title' => $request->input('title'),
            'abstract' => $request->input('abstract'),
            'document_url' => $request->input('document_url'),
            'status' => 'submitted',
        ]);

        // Update research title on PG profile
        PgStudentProfile::where('student_id', $student->id)->update([
            'research_title' => $request->input('title'),
            'current_stage' => 'proposal_defense',
        ]);

        return $this->success($proposal, 'Research proposal submitted for board review.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Supervisor or SPS Board review proposal.
     */
    public function reviewProposal(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:approved,revisions_required',
            'reviewer_feedback' => 'required|string|max:2000',
            'defense_score' => 'nullable|numeric|between:0,100',
            'defense_date' => 'nullable|date',
        ]);

        $proposal = PgProposal::findOrFail($id);
        $proposal->update([
            'status' => $request->input('status'),
            'reviewer_feedback' => $request->input('reviewer_feedback'),
            'defense_score' => $request->input('defense_score'),
            'defense_date' => $request->input('defense_date', now()->toDateString()),
        ]);

        if ($request->input('status') === 'approved') {
            PgStudentProfile::where('student_id', $proposal->student_id)->update([
                'current_stage' => 'internal_defense',
            ]);
        }

        return $this->success($proposal, 'Proposal review saved successfully.');
    }

    /**
     * List thesis chapter milestones.
     */
    public function getMilestones(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $milestones = PgThesisMilestone::where('student_id', $student->id)
            ->orderBy('chapter_number')
            ->get();

        return $this->success($milestones, 'Thesis chapter milestones retrieved.');
    }

    /**
     * Submit a thesis chapter draft.
     */
    public function submitMilestone(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'submission_url' => 'required|url',
        ]);

        $student = $request->user()->student;
        $milestone = PgThesisMilestone::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $milestone->update([
            'submission_url' => $request->input('submission_url'),
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return $this->success($milestone, 'Thesis chapter submitted to supervisor.');
    }

    /**
     * Supervisor review of a thesis chapter.
     */
    public function reviewMilestone(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|in:approved,revisions_needed',
            'supervisor_comments' => 'required|string|max:2000',
        ]);

        $milestone = PgThesisMilestone::findOrFail($id);
        $milestone->update([
            'status' => $request->input('status'),
            'supervisor_comments' => $request->input('supervisor_comments'),
            'reviewed_at' => now(),
        ]);

        return $this->success($milestone, 'Thesis chapter review recorded.');
    }

    /**
     * List supervision meeting logs.
     */
    public function getSupervisionLogs(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        $logs = PgSupervisionLog::where('student_id', $student->id)
            ->with('supervisor.user')
            ->latest('meeting_date')
            ->get();

        return $this->success($logs, 'Supervision meeting logs retrieved.');
    }

    /**
     * Log a supervision meeting.
     */
    public function logSupervisionMeeting(Request $request): JsonResponse
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'meeting_date' => 'required|date',
            'summary_notes' => 'required|string|max:2000',
            'next_deliverables' => 'required|string|max:2000',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $log = PgSupervisionLog::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'staff_id' => $request->input('staff_id'),
            'meeting_date' => $request->input('meeting_date'),
            'summary_notes' => $request->input('summary_notes'),
            'next_deliverables' => $request->input('next_deliverables'),
            'status' => 'pending_approval',
        ]);

        return $this->success($log, 'Supervision meeting logged successfully.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Supervisor confirms meeting log.
     */
    public function confirmSupervisionMeeting(int $id, Request $request): JsonResponse
    {
        $log = PgSupervisionLog::findOrFail($id);
        $log->update(['status' => 'confirmed']);

        return $this->success($log, 'Supervision meeting confirmed by supervisor.');
    }

    /**
     * Early warning radar analytics for Dean and Supervisors.
     */
    public function earlyWarning(SpsEarlyWarningService $spsService): JsonResponse
    {
        $analytics = $spsService->getAnalytics();
        return $this->success($analytics, 'SPS early warning radar analytics retrieved.');
    }
}
