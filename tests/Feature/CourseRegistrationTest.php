<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_course_registration_rejects_under_minimum_credit_limit(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        // Pick only 2 courses = 6 credits (Minimum required is 15)
        $courseIds = Course::where('code', 'LIKE', 'CSC%')->take(2)->pluck('id')->toArray();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/courses/register', [
                'course_ids' => $courseIds,
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonFragment(['message' => 'Total registered credits (6) is below the minimum allowed of 15 credit units.']);
    }

    public function test_student_can_successfully_register_within_credit_limits(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        // Pick 6 courses = 18 credits (Valid range 15 - 24)
        $courseIds = Course::where('code', 'LIKE', 'CSC%')->orWhere('code', 'LIKE', 'MTH%')->take(6)->pluck('id')->toArray();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/courses/register', [
                'course_ids' => $courseIds,
                'academic_session' => '2025/2026',
                'semester' => 'first',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_credits', 18)
            ->assertJsonPath('data.status', 'submitted');
    }

    public function test_hod_can_approve_student_course_registration(): void
    {
        $hodUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();
        $registration = CourseRegistration::first();

        $response = $this->actingAs($hodUser, 'sanctum')
            ->postJson("/api/v1/courses/registrations/{$registration->id}/approve", [
                'status' => 'approved',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'approved');
    }
}
