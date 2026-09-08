<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerAndCvTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_view_career_profile(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/career/profile');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'personalInfo' => ['name', 'email', 'phone', 'programme', 'cgpa', 'standing'],
                    'skills',
                    'projects',
                    'certifications',
                ],
            ]);

        $this->assertNotEmpty($response->json('data.skills'));
        $this->assertNotEmpty($response->json('data.projects'));
        $this->assertNotEmpty($response->json('data.certifications'));
    }

    public function test_student_can_add_skill_project_and_certification(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        // 1. Add skill
        $skillRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/career/skills', [
                'name' => 'Go & Microservices',
                'category' => 'technical',
                'proficiency_level' => 'advanced',
            ]);

        $skillRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Go & Microservices');

        // 2. Add project
        $projRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/career/projects', [
                'title' => 'Decentralized Identity Wallet',
                'description' => 'Zero-knowledge proof credential verification app for student identity tokens.',
                'technologies' => ['Rust', 'Flutter', 'WebAssembly'],
                'github_url' => 'https://github.com/chidi/zk-wallet',
            ]);

        $projRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Decentralized Identity Wallet');

        // 3. Add certification
        $certRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/career/certifications', [
                'title' => 'Certified Kubernetes Administrator (CKA)',
                'issuer' => 'Cloud Native Computing Foundation (CNCF)',
                'issue_date' => '2026-02-10',
                'credential_url' => 'https://cncf.io/verify/CKA-100293',
            ]);

        $certRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Certified Kubernetes Administrator (CKA)');
    }

    public function test_student_can_view_active_job_listings(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/career/jobs?job_type=internship');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $jobs = $response->json('data');
        $this->assertNotEmpty($jobs);
        $this->assertEquals('internship', $jobs[0]['job_type']);
    }

    public function test_student_can_download_ats_compliant_cv_pdf(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->get('/api/v1/career/cv/pdf');

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));

        // Assert response is valid binary PDF starting with '%PDF-'
        $content = $response->getContent();
        $this->assertStringStartsWith('%PDF-', $content);
        $this->assertGreaterThan(1000, strlen($content));
    }
}
