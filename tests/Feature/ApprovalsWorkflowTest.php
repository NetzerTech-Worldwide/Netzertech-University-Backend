<?php

namespace Tests\Feature;

use App\Models\ApprovalRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalsWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_initiate_course_form_signing_request(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/approvals/requests', [
                'type' => 'course_form_signing',
                'title' => 'First Semester 2025/2026 Registration Endorsement',
                'reason' => 'Completed credit registration of 18 units.',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_levels', 2)
            ->assertJsonPath('data.current_level', 1)
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonCount(2, 'data.steps');
    }

    public function test_staff_can_view_requests_pending_their_action(): void
    {
        $hodUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($hodUser, 'sanctum')
            ->getJson('/api/v1/approvals/requests?filter=pending_my_action');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_unauthorized_user_cannot_act_on_step(): void
    {
        // An ordinary student or unassigned staff attempts to approve
        $randomUser = User::where('email', 'amina.yusuf@gmail.com')->first();
        $request = ApprovalRequest::where('type', 'course_form_signing')->first();

        $response = $this->actingAs($randomUser, 'sanctum')
            ->postJson("/api/v1/approvals/requests/{$request->id}/action", [
                'action' => 'approved',
                'comments' => 'Unauthorized approval attempt',
            ]);

        $response->assertStatus(422);
    }

    public function test_full_hierarchical_approval_chain_and_final_approval(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        // Create new request
        $createRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/approvals/requests', [
                'type' => 'course_form_signing',
                'title' => 'Endorsement for 300L Course Form',
                'reason' => 'Full registration done.',
            ]);

        $requestId = $createRes->json('data.id');

        // Level 1: HOD Approves
        $hodUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();
        $hodActionRes = $this->actingAs($hodUser, 'sanctum')
            ->postJson("/api/v1/approvals/requests/{$requestId}/action", [
                'action' => 'approved',
                'comments' => 'Prerequisites fulfilled and course form verified.',
            ]);

        $hodActionRes->assertStatus(200)
            ->assertJsonPath('data.current_level', 2)
            ->assertJsonPath('data.status', 'in_review');

        // Level 2: Dean Approves (Final Level)
        $deanUser = User::where('email', 'chioma.eze@novicauniversity.edu.ng')->first();
        $deanActionRes = $this->actingAs($deanUser, 'sanctum')
            ->postJson("/api/v1/approvals/requests/{$requestId}/action", [
                'action' => 'approved',
                'comments' => 'Dean endorsement granted.',
            ]);

        $deanActionRes->assertStatus(200)
            ->assertJsonPath('data.status', 'approved');
    }

    public function test_rejection_at_any_level_terminates_request(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $createRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/approvals/requests', [
                'type' => 'deferral_of_exams',
                'title' => 'Application to Defer Examinations',
                'reason' => 'Severe personal hardship.',
            ]);

        $requestId = $createRes->json('data.id');

        // HOD Rejects
        $hodUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();
        $rejectRes = $this->actingAs($hodUser, 'sanctum')
            ->postJson("/api/v1/approvals/requests/{$requestId}/action", [
                'action' => 'rejected',
                'comments' => 'Supporting evidence insufficient.',
            ]);

        $rejectRes->assertStatus(200)
            ->assertJsonPath('data.status', 'rejected');
    }
}
