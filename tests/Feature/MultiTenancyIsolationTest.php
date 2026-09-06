<?php

namespace Tests\Feature;

use App\Models\University;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenancyIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_programmes_are_strictly_isolated_by_university_tenant(): void
    {
        // 1. Query programmes under Novica University (NVU)
        $responseNovica = $this->withHeader('X-University-Code', 'NVU')
            ->getJson('/api/v1/admissions/programmes');

        $responseNovica->assertStatus(200)
            ->assertJsonPath('success', true);

        $novicaFaculties = collect($responseNovica->json('data'));
        $this->assertTrue($novicaFaculties->contains('name', 'Faculty of Computing & Information Technology'));
        $this->assertFalse($novicaFaculties->contains('name', 'Faculty of Engineering & Emerging Technologies'));

        // 2. Query programmes under Apex Premier University (APEX)
        $responseApex = $this->withHeader('X-University-Code', 'APEX')
            ->getJson('/api/v1/admissions/programmes');

        $responseApex->assertStatus(200)
            ->assertJsonPath('success', true);

        $apexFaculties = collect($responseApex->json('data'));
        $this->assertTrue($apexFaculties->contains('name', 'Faculty of Engineering & Emerging Technologies'));
        $this->assertFalse($apexFaculties->contains('name', 'Faculty of Computing & Information Technology'));
    }

    public function test_cross_university_login_is_blocked(): void
    {
        // Attempt to log into Apex Premier University using Novica Student credentials
        $response = $this->withHeader('X-University-Code', 'APEX')
            ->postJson('/api/v1/auth/login', [
                'identifier' => 'chidi@novicauniversity.edu.ng',
                'password' => 'password123',
            ]);

        $response->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'These credentials do not belong to the selected university.');
    }

    public function test_student_can_login_to_their_designated_university(): void
    {
        // 1. Novica student logs into Novica
        $responseNovica = $this->withHeader('X-University-Code', 'NVU')
            ->postJson('/api/v1/auth/login', [
                'identifier' => 'chidi@novicauniversity.edu.ng',
                'password' => 'password123',
            ]);

        $responseNovica->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.university.code', 'NVU')
            ->assertJsonPath('data.user.student.matric_number', 'NVU/2021/CSC/001');

        // 2. Apex student logs into Apex
        $responseApex = $this->withHeader('X-University-Code', 'APEX')
            ->postJson('/api/v1/auth/login', [
                'identifier' => 'tunde.bakare@apex.edu.ng',
                'password' => 'password123',
            ]);

        $responseApex->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.university.code', 'APEX')
            ->assertJsonPath('data.user.student.matric_number', 'APEX/2022/SWE/001');
    }

    public function test_admissions_registration_is_automatically_scoped_to_tenant(): void
    {
        // Register applicant on Apex Premier University
        $response = $this->withHeader('X-University-Code', 'APEX')
            ->postJson('/api/v1/admissions/register', [
                'name' => 'Maryam Ibrahim',
                'email' => 'maryam.ibrahim@example.com',
                'phone' => '08077665544',
                'password' => 'secret123',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $appNumber = $response->json('data.application_no');
        $this->assertStringStartsWith('APP/APEX/', $appNumber);
    }
}
