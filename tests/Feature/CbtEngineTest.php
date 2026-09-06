<?php

namespace Tests\Feature;

use App\Models\CbtExam;
use App\Models\CbtStudentSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CbtEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_start_cbt_exam_and_receive_questions_without_leaking_answers(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $exam = CbtExam::first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson("/api/v1/cbt/exams/{$exam->id}/start");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.exam.id', $exam->id);

        $data = $response->json('data');
        $this->assertNotEmpty($data['questions']);

        // Assert correct answer is never leaked to the student in the question payload!
        foreach ($data['questions'] as $q) {
            $this->assertArrayNotHasKey('correct_answer', $q);
        }
    }

    public function test_heartbeat_autosaves_answers_and_submit_auto_scores(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $exam = CbtExam::with('questions')->first();

        // 1. Start Exam
        $startRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson("/api/v1/cbt/exams/{$exam->id}/start");
        $sessionId = $startRes->json('data.session_id');

        // 2. Heartbeat Autosave
        $q1 = $exam->questions[0];
        $q2 = $exam->questions[1];
        $q3 = $exam->questions[2];

        $answers = [
            $q1->id => $q1->correct_answer, // Correct
            $q2->id => $q2->correct_answer, // Correct
            $q3->id => 'WRONG_ANSWER',      // Wrong
        ];

        $heartbeatRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson("/api/v1/cbt/sessions/{$sessionId}/heartbeat", [
                'answers' => $answers,
            ]);

        $heartbeatRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.answers_saved', 3);

        // 3. Final Submit and Auto-Grade
        $submitRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson("/api/v1/cbt/sessions/{$sessionId}/submit", [
                'answers' => $answers,
            ]);

        $submitRes->assertStatus(200)
            ->assertJsonPath('success', true);

        // q1 (3.5) + q2 (3.5) = 7.00 out of 10.00 = 70.00%
        $score = $submitRes->json('data.score_obtained');
        $percentage = $submitRes->json('data.percentage');

        $this->assertEquals(7.00, $score);
        $this->assertEquals(70.00, $percentage);
        $this->assertTrue($submitRes->json('data.passed'));
    }
}
