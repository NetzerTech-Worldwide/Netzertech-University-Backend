<?php

namespace App\Services\Approvals;

use App\Models\ApprovalRequest;
use App\Models\ApprovalStep;
use App\Models\CourseRegistration;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApprovalWorkflowService
{
    /**
     * Create an approval request and instantiate the hierarchical step chain.
     */
    public function createRequest(Student $student, string $type, string $title, string $reason, ?string $docUrl = null, ?array $metadata = null): ApprovalRequest
    {
        $student->load(['department', 'faculty']);

        $hodStaffId = $student->department?->hod_staff_id;
        $deanStaffId = $student->faculty?->dean_staff_id;

        // Build approval sequence based on regulatory institution rules
        $chain = match ($type) {
            'course_form_signing' => [
                ['level' => 1, 'role' => 'hod', 'staff_id' => $hodStaffId],
                ['level' => 2, 'role' => 'dean', 'staff_id' => $deanStaffId],
            ],
            'transcript_request' => [
                ['level' => 1, 'role' => 'hod', 'staff_id' => $hodStaffId],
                ['level' => 2, 'role' => 'bursar', 'staff_id' => null],
                ['level' => 3, 'role' => 'registrar', 'staff_id' => null],
            ],
            'deferral_of_exams' => [
                ['level' => 1, 'role' => 'hod', 'staff_id' => $hodStaffId],
                ['level' => 2, 'role' => 'dean', 'staff_id' => $deanStaffId],
                ['level' => 3, 'role' => 'exam_officer', 'staff_id' => null],
            ],
            'change_of_course' => [
                ['level' => 1, 'role' => 'hod', 'staff_id' => $hodStaffId],
                ['level' => 2, 'role' => 'dean', 'staff_id' => $deanStaffId],
                ['level' => 3, 'role' => 'registrar', 'staff_id' => null],
            ],
            'medical_leave' => [
                ['level' => 1, 'role' => 'medical_officer', 'staff_id' => null],
                ['level' => 2, 'role' => 'hod', 'staff_id' => $hodStaffId],
            ],
            default => [
                ['level' => 1, 'role' => 'hod', 'staff_id' => $hodStaffId],
            ],
        };

        return DB::transaction(function () use ($student, $type, $title, $reason, $docUrl, $metadata, $chain) {
            $ref = 'REQ-' . now()->format('Y') . '-' . strtoupper(Str::random(6));

            $request = ApprovalRequest::create([
                'university_id' => $student->university_id,
                'student_id' => $student->id,
                'request_ref' => $ref,
                'type' => $type,
                'title' => $title,
                'reason' => $reason,
                'supporting_document_url' => $docUrl,
                'total_levels' => count($chain),
                'current_level' => 1,
                'status' => 'pending',
                'metadata' => $metadata,
            ]);

            foreach ($chain as $stepConfig) {
                ApprovalStep::create([
                    'university_id' => $student->university_id,
                    'approval_request_id' => $request->id,
                    'assigned_staff_id' => $stepConfig['staff_id'],
                    'required_role' => $stepConfig['role'],
                    'level_number' => $stepConfig['level'],
                    'action' => 'pending',
                ]);
            }

            return $request->load('steps');
        });
    }

    /**
     * Process an action (approved or rejected) for the active step in the chain.
     */
    public function processAction(ApprovalRequest $request, User $user, string $action, ?string $comments = null): ApprovalRequest
    {
        if (!in_array($action, ['approved', 'rejected'])) {
            throw new InvalidArgumentException("Invalid action: must be 'approved' or 'rejected'.");
        }

        if (in_array($request->status, ['approved', 'rejected'])) {
            throw new InvalidArgumentException("This request is already finalized with status: {$request->status}.");
        }

        return DB::transaction(function () use ($request, $user, $action, $comments) {
            $step = $request->steps()
                ->where('level_number', $request->current_level)
                ->lockForUpdate()
                ->firstOrFail();

            // Check authorization: user must have the step role or super_admin or match assigned_staff_id
            $hasRole = $user->hasRole($step->required_role) || $user->hasRole('super_admin');
            $isAssigned = $step->assigned_staff_id && $user->staff && ($user->staff->id === $step->assigned_staff_id);

            if (!$hasRole && !$isAssigned) {
                throw new AccessDeniedHttpException("You do not have permission to act on this approval level (Requires: {$step->required_role}).");
            }

            $step->update([
                'action' => $action,
                'comments' => $comments,
                'acted_by_user_id' => $user->id,
                'acted_at' => now(),
            ]);

            if ($action === 'rejected') {
                $request->update(['status' => 'rejected']);
            } else {
                if ($request->current_level < $request->total_levels) {
                    $request->update([
                        'current_level' => $request->current_level + 1,
                        'status' => 'in_review',
                    ]);
                } else {
                    $request->update(['status' => 'approved']);

                    // Fulfill side-effects of approval
                    if ($request->type === 'course_form_signing') {
                        $reg = CourseRegistration::where('student_id', $request->student_id)
                            ->latest()
                            ->first();
                        if ($reg) {
                            $reg->update([
                                'status' => 'approved',
                                'approved_at' => now(),
                                'approved_by_staff_id' => $user->staff?->id,
                            ]);
                        }
                    }
                }
            }

            return $request->fresh(['steps.assignedStaff', 'steps.actedByUser', 'student.user']);
        });
    }
}
