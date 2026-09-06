<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use App\Services\Academics\GpaCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NucGpaComputationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_nuc_grading_scale_points(): void
    {
        // 70+ -> A (5.0)
        $a = GpaCalculatorService::getGradeAndPoint(78.5);
        $this->assertEquals('A', $a['grade']);
        $this->assertEquals(5.00, $a['grade_point']);

        // 60-69 -> B (4.0)
        $b = GpaCalculatorService::getGradeAndPoint(64.0);
        $this->assertEquals('B', $b['grade']);
        $this->assertEquals(4.00, $b['grade_point']);

        // 50-59 -> C (3.0)
        $c = GpaCalculatorService::getGradeAndPoint(53.0);
        $this->assertEquals('C', $c['grade']);
        $this->assertEquals(3.00, $c['grade_point']);

        // 45-49 -> D (2.0)
        $d = GpaCalculatorService::getGradeAndPoint(48.0);
        $this->assertEquals('D', $d['grade']);
        $this->assertEquals(2.00, $d['grade_point']);

        // 0-44 -> F (0.0)
        $f = GpaCalculatorService::getGradeAndPoint(39.0);
        $this->assertEquals('F', $f['grade']);
        $this->assertEquals(0.00, $f['grade_point']);
    }

    public function test_lecturer_score_upload_triggers_gpa_and_cgpa_calculation(): void
    {
        $lecturerUser = User::where('email', 'adebayo.olatunji@novicauniversity.edu.ng')->first();
        $student = Student::first();
        $course = Course::where('code', 'CSC 301')->first();

        $response = $this->actingAs($lecturerUser, 'sanctum')
            ->postJson('/api/v1/results/upload', [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'academic_session' => '2025/2026',
                'semester' => 'first',
                'ca_score' => 28.00,
                'exam_score' => 57.00, // Total = 85 -> Grade A -> 5.0
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.course_result.grade', 'A')
            ->assertJsonPath('data.course_result.grade_point', '5.00');

        // Verify updated student CGPA is non-zero
        $student->refresh();
        $this->assertGreaterThan(0.00, (float)$student->cgpa);
    }

    public function test_student_can_view_semester_results(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->getJson('/api/v1/results/semester?session=2025/2026&semester=first');

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertNotEmpty($response->json('data.courses'));
        $this->assertNotNull($response->json('data.summary.gpa'));
    }

    public function test_student_can_download_official_transcript_pdf(): void
    {
        $studentUser = User::where('email', 'chidi@novicauniversity.edu.ng')->first();

        $response = $this->actingAs($studentUser, 'sanctum')
            ->get('/api/v1/results/transcript');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf');

        $this->assertNotEmpty($response->getContent());
    }
}
