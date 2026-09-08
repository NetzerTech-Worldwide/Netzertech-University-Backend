<?php

namespace App\Http\Controllers\Api\V1\Approvals;

use App\Http\Controllers\Controller;
use App\Models\ApprovalRequest;
use App\Services\Approvals\ApprovalWorkflowService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class ApprovalController extends Controller
{
    use ApiResponse;

    /**
     * List approval requests for the authenticated student or pending requests for staff.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = ApprovalRequest::with(['steps.assignedStaff.user', 'steps.actedByUser', 'student.user', 'student.department']);

        if ($user->hasRole('student')) {
            $student = $user->student;
            if (!$student) {
                return $this->error('Student profile not found.', HttpStatus::HTTP_NOT_FOUND);
            }
            $query->where('student_id', $student->id);
        } elseif ($request->query('filter') === 'pending_my_action') {
            // Filter requests where the current step matches this staff's assigned ID or role
            $userRoles = $user->getRoleNames()->toArray();
            $staffId = $user->staff?->id;

            $query->whereIn('status', ['pending', 'in_review'])
                ->whereHas('steps', function ($stepQuery) use ($userRoles, $staffId) {
                    $stepQuery->whereColumn('level_number', 'approval_requests.current_level')
                        ->where('action', 'pending')
                        ->where(function ($q) use ($userRoles, $staffId) {
                            $q->whereIn('required_role', $userRoles);
                            if ($staffId) {
                                $q->orWhere('assigned_staff_id', $staffId);
                            }
                        });
                });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $requests = $query->latest()->get();

        return $this->success($requests, 'Approval requests retrieved successfully.');
    }

    /**
     * Initiate a multi-tier academic or administrative approval request.
     */
    public function store(Request $request, ApprovalWorkflowService $workflowService): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:course_form_signing,transcript_request,deferral_of_exams,change_of_course,medical_leave,custom',
            'title' => 'required|string|max:255',
            'reason' => 'required|string|max:2000',
            'supporting_document_url' => 'nullable|url',
            'metadata' => 'nullable|array',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Only students can submit approval requests.', HttpStatus::HTTP_FORBIDDEN);
        }

        try {
            $created = $workflowService->createRequest(
                $student,
                $request->input('type'),
                $request->input('title'),
                $request->input('reason'),
                $request->input('supporting_document_url'),
                $request->input('metadata')
            );

            return $this->success($created, 'Approval request initiated successfully.', HttpStatus::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * View detailed timeline and steps for a specific request.
     */
    public function show(int $id): JsonResponse
    {
        $request = ApprovalRequest::with([
            'steps.assignedStaff.user',
            'steps.actedByUser',
            'student.user',
            'student.department',
            'student.faculty',
        ])->findOrFail($id);

        return $this->success($request, 'Approval request details retrieved.');
    }

    /**
     * Process an action on the active approval step (Staff).
     */
    public function action(int $id, Request $request, ApprovalWorkflowService $workflowService): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:approved,rejected',
            'comments' => 'nullable|string|max:1000',
        ]);

        $approvalRequest = ApprovalRequest::findOrFail($id);
        $user = $request->user();

        try {
            $updated = $workflowService->processAction(
                $approvalRequest,
                $user,
                $request->input('action'),
                $request->input('comments')
            );

            return $this->success($updated, "Approval request step {$request->input('action')} successfully.");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), HttpStatus::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
