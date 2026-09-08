<?php

namespace Tests\Feature;

use App\Models\DigitalIdCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DigitalIdAndClinicTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_fetch_digital_id_card(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/identity/card');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'card_number',
                    'barcode_hash',
                    'qr_verification_url',
                    'student' => ['name', 'matric_number', 'department', 'level'],
                    'university' => ['name', 'code'],
                ],
            ]);
    }

    public function test_student_can_download_printable_pdf_id_card(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->get('/api/v1/identity/card/pdf');

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    public function test_public_qr_verification_endpoint_validates_student_identity_without_auth(): void
    {
        $card = DigitalIdCard::first();

        $response = $this->getJson("/api/v1/verify/id-card/{$card->barcode_hash}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.valid', true)
            ->assertJsonPath('data.status', 'ACTIVE & VERIFIED')
            ->assertJsonPath('data.student.matric_number', $card->student->matric_number);
    }

    public function test_student_can_register_clinic_bio_data_and_view_digital_hospital_card(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $regResponse = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/facilities/clinic/register', [
                'blood_group' => 'O+',
                'genotype' => 'AA',
                'allergies' => 'Penicillin, Peanuts',
                'chronic_conditions' => 'Asthma (controlled)',
                'emergency_contact_name' => 'Mrs. Grace Okonkwo',
                'emergency_contact_phone' => '08099887766',
                'emergency_contact_relation' => 'Mother',
            ]);

        $regResponse->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.blood_group', 'O+')
            ->assertJsonPath('data.is_cleared', true);

        // Fetch hospital card
        $cardResponse = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/facilities/clinic/card');

        $cardResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.bloodGroup', 'O+')
            ->assertJsonPath('data.genotype', 'AA')
            ->assertJsonPath('data.emergencyContact', 'Mrs. Grace Okonkwo');
    }

    public function test_student_can_schedule_clinic_appointment(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/facilities/clinic/appointments', [
                'visit_date' => now()->addDays(2)->toDateString(),
                'symptoms' => 'Persistent cough and sore throat.',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'scheduled');
    }
}
