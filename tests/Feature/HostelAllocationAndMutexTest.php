<?php

namespace Tests\Feature;

use App\Models\BedSpace;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostelAllocationAndMutexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_student_can_list_available_hostels_and_view_bed_capacities(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/facilities/hostels');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'code', 'gender', 'campus_location', 'capacity', 'total_beds', 'available_beds'],
                ],
            ]);
    }

    public function test_gender_policy_blocks_male_student_from_booking_female_hostel(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first(); // Male student
        $femaleHostel = Hostel::where('gender', 'female')->first();

        // Create an available room and bed in female hostel
        $room = HostelRoom::create([
            'university_id' => $femaleHostel->university_id,
            'hostel_id' => $femaleHostel->id,
            'room_number' => '102',
            'capacity' => 4,
            'allocated_count' => 0,
            'fee_amount' => 45000.00,
        ]);
        $bed = BedSpace::create([
            'university_id' => $femaleHostel->university_id,
            'hostel_room_id' => $room->id,
            'bed_label' => 'Bed A',
            'status' => 'available',
        ]);

        $response = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/facilities/hostels/reserve-bed', [
                'bed_space_id' => $bed->id,
                'academic_session' => '2026/2027',
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonFragment(['message' => "Gender restriction: {$femaleHostel->name} is reserved for female residents."]);
    }

    public function test_bed_space_reservation_locks_space_and_increments_room_occupancy(): void
    {
        $faculty = \App\Models\Faculty::first();
        $dept = \App\Models\Department::first();
        $prog = \App\Models\Programme::first();

        // Use a new student who has no existing allocation
        $newStudentUser = User::factory()->create([
            'university_id' => 1,
            'user_type' => 'student',
        ]);
        $newStudentUser->assignRole('student');
        $student = Student::create([
            'university_id' => 1,
            'user_id' => $newStudentUser->id,
            'matric_number' => 'NVU/2023/CSC/999',
            'faculty_id' => $faculty->id,
            'department_id' => $dept->id,
            'programme_id' => $prog->id,
            'level' => '100L',
            'academic_session' => '2025/2026',
        ]);
        $student->profile()->create([
            'gender' => 'male',
        ]);

        // Find an available bed space in male hostel
        $availableBed = BedSpace::where('status', 'available')
            ->whereHas('room.hostel', fn($q) => $q->where('gender', 'male'))
            ->first();

        $room = $availableBed->room;
        $initialOccupancy = $room->allocated_count;

        $response = $this->actingAs($newStudentUser, 'sanctum')
            ->postJson('/api/v1/facilities/hostels/reserve-bed', [
                'bed_space_id' => $availableBed->id,
                'academic_session' => '2025/2026',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.bed.status', 'occupied');

        $this->assertEquals('occupied', $availableBed->fresh()->status);
        $this->assertEquals($initialOccupancy + 1, $room->fresh()->allocated_count);

        // Attempting to reserve the exact same bed space again must fail
        $anotherUser = User::factory()->create(['university_id' => 1, 'user_type' => 'student']);
        $anotherUser->assignRole('student');
        $anotherStudent = Student::create([
            'university_id' => 1,
            'user_id' => $anotherUser->id,
            'matric_number' => 'NVU/2023/CSC/998',
            'faculty_id' => $faculty->id,
            'department_id' => $dept->id,
            'programme_id' => $prog->id,
            'level' => '100L',
            'academic_session' => '2025/2026',
        ]);
        $anotherStudent->profile()->create(['gender' => 'male']);


        $conflictResponse = $this->actingAs($anotherUser, 'sanctum')
            ->postJson('/api/v1/facilities/hostels/reserve-bed', [
                'bed_space_id' => $availableBed->id,
                'academic_session' => '2025/2026',
            ]);

        $conflictResponse->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonFragment(['message' => 'This bed space is no longer available. Please select an available bed.']);
    }

    public function test_student_can_view_my_allocation_and_sign_rules_agreement(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        // 1. View allocation
        $viewRes = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/facilities/hostels/my-allocation');

        $viewRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.hostel.name', 'Kings Hall')
            ->assertJsonPath('data.room.room_number', '204')
            ->assertJsonPath('data.assigned_bed', 'Bed B');

        // 2. Sign agreement
        $signRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/facilities/hostels/agreement');

        $signRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.rules_agreed', true);
    }

    public function test_student_can_submit_hostel_maintenance_ticket_and_staff_resolves_it(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();
        $room = HostelRoom::first();

        // Student submits ticket
        $submitRes = $this->actingAs($studentUser, 'sanctum')
            ->postJson('/api/v1/facilities/hostels/maintenance', [
                'hostel_room_id' => $room->id,
                'category' => 'electrical',
                'priority' => 'high',
                'description' => 'Ceiling fan regulator short-circuited and sparked.',
            ]);

        $submitRes->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'open');

        $ticketId = $submitRes->json('data.id');

        // Staff / Hostel Master resolves ticket
        $staffUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();

        $resolveRes = $this->actingAs($staffUser, 'sanctum')
            ->postJson("/api/v1/facilities/hostels/maintenance/{$ticketId}/resolve", [
                'resolution_notes' => 'Works department electrician replaced the speed regulator capacitor.',
            ]);

        $resolveRes->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'resolved');
    }
}
