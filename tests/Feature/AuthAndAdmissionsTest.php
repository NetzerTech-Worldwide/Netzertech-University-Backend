<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAdmissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_login_with_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'identifier' => 'chidi@novicauniversity.edu.ng',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'token_type',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'user_type',
                        'student' => [
                            'matric_number',
                            'level',
                            'cgpa',
                            'faculty',
                            'department',
                            'programme',
                        ],
                    ],
                ],
            ]);
    }

    public function test_student_can_login_with_matric_number(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'identifier' => 'NVU/2021/CSC/001',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.name', 'Chidi Okonkwo');
    }

    public function test_staff_can_login(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'identifier' => 'chioma.eze@novicauniversity.edu.ng',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.staff.designation', 'Dean, Faculty of Computing & IT');
    }

    public function test_applicant_registration_and_status_flow(): void
    {
        // 1. Register Applicant
        $regResponse = $this->postJson('/api/v1/admissions/register', [
            'name' => 'Kelechi Emmanuel',
            'email' => 'kelechi@example.com',
            'phone' => '08099887766',
            'password' => 'secret123',
        ]);

        $regResponse->assertStatus(201)
            ->assertJsonPath('success', true);

        $token = $regResponse->json('data.token');

        // 2. Save Personal Info Step
        $stepResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/admissions/steps/personal', [
                'gender' => 'male',
                'state_of_origin' => 'Enugu',
                'lga' => 'Nsukka',
                'nationality' => 'Nigerian',
                'religion' => 'Christianity',
            ]);

        $stepResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        // 3. Save JAMB Record Step
        $jambResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/admissions/steps/jamb', [
                'reg_number' => '2026887711AA',
                'score' => 290,
                'year' => 2026,
                'institution_chosen' => 'Netzertech University',
                'course_chosen' => 'Computer Science',
            ]);

        $jambResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        // 4. Save Programme Step
        $progResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/admissions/steps/programme', [
                'first_choice_programme_id' => 1,
            ]);

        $progResponse->assertStatus(200)
            ->assertJsonPath('success', true);

        // 5. Submit Application
        $submitResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/admissions/submit');

        $submitResponse->assertStatus(200)
            ->assertJsonPath('data.status', 'under_review')
            ->assertJsonPath('data.app_fee_paid', true);

        // 6. Accept Offer & Matriculate
        $acceptResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/admissions/offer/accept');

        $acceptResponse->assertStatus(200)
            ->assertJsonPath('data.offer_accepted', true);

        $matricResponse = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/admissions/matriculate');

        $matricResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'matric_number',
                    'student',
                ],
            ]);
    }
}
