<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\StudyGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyGroupsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_view_active_study_groups(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/collaboration/study-groups');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $groups = $response->json('data');
        $this->assertNotEmpty($groups);
        $this->assertArrayHasKey('members_count', $groups[0]);
    }

    public function test_student_can_create_study_group_and_is_assigned_lead(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $course = Course::first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/collaboration/study-groups', [
                'name' => 'Automata Theory & Computability Pod',
                'course_id' => $course->id,
                'description' => 'Weekly proofs and Turing machine reductions.',
                'max_members' => 6,
                'meeting_schedule' => 'Fridays 3:00 PM',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Automata Theory & Computability Pod');

        $groupId = $response->json('data.id');
        $group = StudyGroup::with('members')->findOrFail($groupId);

        $this->assertEquals(1, $group->members->count());
        $this->assertEquals('lead', $group->members->first()->role);
        $this->assertEquals($studentUser->student->id, $group->members->first()->student_id);
    }

    public function test_peer_student_can_join_group_and_exchange_messages(): void
    {
        $pgUser = User::where('email', 'fatima.pg@novicauniversity.edu.ng')->first();
        $group = StudyGroup::first();

        // 1. Join group
        $joinRes = $this->actingAs($pgUser, 'sanctum')
            ->postJson("/api/v1/collaboration/study-groups/{$group->id}/join");

        $joinRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.role', 'member');

        // 2. Post discussion message
        $msgRes = $this->actingAs($pgUser, 'sanctum')
            ->postJson("/api/v1/collaboration/study-groups/{$group->id}/messages", [
                'message' => 'Thanks for having me. I will prepare the notes for graph algorithms.',
            ]);

        $msgRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.message', 'Thanks for having me. I will prepare the notes for graph algorithms.');

        // 3. View message thread
        $threadRes = $this->actingAs($pgUser, 'sanctum')
            ->getJson("/api/v1/collaboration/study-groups/{$group->id}/messages");

        $threadRes->assertStatus(200)
            ->assertJsonPath('success', true);

        $messages = $threadRes->json('data');
        $this->assertGreaterThanOrEqual(2, count($messages));
    }

    public function test_non_member_cannot_post_message_to_group(): void
    {
        $pgUser = User::where('email', 'fatima.pg@novicauniversity.edu.ng')->first();
        $group = StudyGroup::first();

        // Fatima hasn't joined yet, posting must fail with 403 Forbidden
        $response = $this->actingAs($pgUser, 'sanctum')
            ->postJson("/api/v1/collaboration/study-groups/{$group->id}/messages", [
                'message' => 'Intruder message without joining.',
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('success', false);
    }
}
