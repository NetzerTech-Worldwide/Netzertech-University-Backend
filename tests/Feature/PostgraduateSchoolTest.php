<?php

namespace Tests\Feature;

use App\Models\PgProposal;
use App\Models\PgThesisMilestone;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostgraduateSchoolTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_pg_candidate_can_view_sps_dashboard(): void
    {
        $pgUser = User::where('email', 'fatima.pg@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($pgUser, 'sanctum')
            ->getJson('/api/v1/postgraduate/dashboard');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.candidate.matric_number', 'NVU/PG/2024/0088')
            ->assertJsonStructure([
                'success',
                'data' => [
                    'profile',
                    'candidate',
                    'research' => ['title', 'current_stage', 'primary_supervisor'],
                    'proposal',
                    'milestones_progress' => ['total_chapters', 'approved_chapters', 'chapters'],
                    'risk_telemetry' => ['composite_risk_score', 'risk_level', 'breakdown'],
                ],
            ]);
    }

    public function test_pg_candidate_can_submit_research_proposal(): void
    {
        $pgUser = User::where('email', 'fatima.pg@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($pgUser, 'sanctum')
            ->postJson('/api/v1/postgraduate/proposals', [
                'title' => 'Quantum-Resistant Cryptographic Handshakes for Low-Power Edge Sensors',
                'abstract' => 'This thesis models post-quantum lattice-based signature compression for constrained microcontrollers.',
                'document_url' => 'https://novica.edu.ng/pg/proposals/quantum_thesis.pdf',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Quantum-Resistant Cryptographic Handshakes for Low-Power Edge Sensors')
            ->assertJsonPath('data.status', 'submitted');
    }

    public function test_supervisor_can_review_proposal(): void
    {
        $hodUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();
        $proposal = PgProposal::first();

        $response = $this->actingAs($hodUser, 'sanctum')
            ->postJson("/api/v1/postgraduate/proposals/{$proposal->id}/review", [
                'status' => 'approved',
                'reviewer_feedback' => 'Exceptional technical depth. Defense committee recommends unconditional pass.',
                'defense_score' => 92.50,
                'defense_date' => now()->toDateString(),
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'approved')
            ->assertJsonPath('data.defense_score', '92.50');
    }

    public function test_pg_candidate_can_submit_thesis_chapter(): void
    {
        $pgUser = User::where('email', 'fatima.pg@novicauniversity.edu.ng')->first();
        $chapter5 = PgThesisMilestone::where('student_id', $pgUser->student->id)
            ->where('chapter_number', 5)
            ->first();

        $response = $this->actingAs($pgUser, 'sanctum')
            ->postJson("/api/v1/postgraduate/milestones/{$chapter5->id}/submit", [
                'submission_url' => 'https://novica.edu.ng/pg/thesis/ch5_draft.pdf',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'submitted')
            ->assertJsonPath('data.submission_url', 'https://novica.edu.ng/pg/thesis/ch5_draft.pdf');
    }

    public function test_supervisor_can_review_milestone(): void
    {
        $hodUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();
        $milestone = PgThesisMilestone::where('status', 'submitted')->first();

        $response = $this->actingAs($hodUser, 'sanctum')
            ->postJson("/api/v1/postgraduate/milestones/{$milestone->id}/review", [
                'status' => 'approved',
                'supervisor_comments' => 'Empirical benchmarks are statistically sound. Approved to integrate into final bound dissertation.',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'approved');
    }

    public function test_pg_supervision_meeting_can_be_logged_and_confirmed(): void
    {
        $pgUser = User::where('email', 'fatima.pg@novicauniversity.edu.ng')->first();
        $hodUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();

        // 1. Candidate logs meeting
        $logRes = $this->actingAs($pgUser, 'sanctum')
            ->postJson('/api/v1/postgraduate/supervision-logs', [
                'staff_id' => $hodUser->staff->id,
                'meeting_date' => now()->toDateString(),
                'summary_notes' => 'Discussed final edits on Chapter 4 and journal submission to IEEE IoT Journal.',
                'next_deliverables' => 'Submit camera-ready paper by next Friday.',
            ]);

        $logRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pending_approval');

        $logId = $logRes->json('data.id');

        // 2. Supervisor confirms meeting
        $confirmRes = $this->actingAs($hodUser, 'sanctum')
            ->postJson("/api/v1/postgraduate/supervision-logs/{$logId}/confirm");

        $confirmRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'confirmed');
    }

    public function test_dean_can_view_early_warning_radar_analytics(): void
    {
        $deanUser = User::where('email', 'chioma.eze@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($deanUser, 'sanctum')
            ->getJson('/api/v1/postgraduate/early-warning');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'total_candidates',
                    'high_risk_count',
                    'medium_risk_count',
                    'low_risk_count',
                    'candidates',
                ],
            ]);
    }
}
