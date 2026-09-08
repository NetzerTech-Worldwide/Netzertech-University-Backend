<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AIAdvisorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_chat_with_context_aware_ai_advisor(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/ai/advisor/chat', [
                'prompt' => 'What is my current CGPA, academic standing, and what courses should I focus on to graduate with honors?',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'session_id',
                    'response',
                    'suggested_actions',
                    'telemetry' => [
                        'cgpa',
                        'standing',
                        'level',
                        'registered_credits',
                    ],
                ],
            ]);

        // Verify telemetry injected student's live CGPA
        $student = $studentUser->student->fresh();
        $this->assertNotEmpty($response->json('data.response'));
        $this->assertEquals((float) $student->cgpa, (float) $response->json('data.telemetry.cgpa'));
        $this->assertEquals($student->standing, $response->json('data.telemetry.standing'));
    }

    public function test_conversation_history_is_recorded_and_persisted(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        // 1. Send first turn
        $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/ai/advisor/chat', [
                'prompt' => 'Hello advisor, what is my current standing?',
            ]);

        // 2. Fetch history
        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/ai/advisor/history');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data'); // 1 student prompt + 1 advisor reply

        $messages = $response->json('data');
        $this->assertEquals('student', $messages[0]['role']);
        $this->assertEquals('advisor', $messages[1]['role']);
    }

    public function test_student_can_clear_conversation_history(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        // 1. Send prompt
        $chatRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/ai/advisor/chat', [
                'prompt' => 'Can I overload credits this semester?',
            ]);

        $sessionId = $chatRes->json('data.session_id');

        // 2. Clear history
        $clearRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/ai/advisor/clear', [
                'session_id' => $sessionId,
            ]);

        $clearRes->assertStatus(200)
            ->assertJsonPath('success', true);

        // 3. Verify history is empty
        $historyRes = $this->actingAs($studentUser, 'sanctum')
            ->getJson("/api/v1/ai/advisor/history?session_id={$sessionId}");

        $historyRes->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }
}
