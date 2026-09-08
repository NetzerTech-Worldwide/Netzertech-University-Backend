<?php

namespace App\Http\Controllers\Api\V1\Career;

use App\Http\Controllers\Controller;
use App\Models\CareerJob;
use App\Models\StudentCertification;
use App\Models\StudentProject;
use App\Models\StudentSkill;
use App\Services\Career\CvGeneratorService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class CareerController extends Controller
{
    use ApiResponse;

    /**
     * Get student career profile: skills, portfolio projects, and certifications.
     */
    public function profile(Request $request): JsonResponse
    {
        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_NOT_FOUND);
        }

        $student->load(['skills', 'projects', 'certifications', 'programme']);

        return $this->success([
            'personalInfo' => [
                'name' => $student->user->name,
                'email' => $student->user->email,
                'phone' => $student->user->phone,
                'programme' => $student->programme?->name,
                'cgpa' => number_format($student->cgpa, 2),
                'standing' => $student->standing,
            ],
            'skills' => $student->skills,
            'projects' => $student->projects,
            'certifications' => $student->certifications,
        ], 'Career profile retrieved successfully.');
    }

    /**
     * Add a technical or professional skill.
     */
    public function addSkill(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|in:technical,soft,language,tool',
            'proficiency_level' => 'nullable|string|in:beginner,intermediate,advanced,expert',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $skill = StudentSkill::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'name' => $request->input('name'),
            'category' => $request->input('category', 'technical'),
            'proficiency_level' => $request->input('proficiency_level', 'intermediate'),
        ]);

        return $this->success($skill, 'Skill added to profile.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Add a portfolio project.
     */
    public function addProject(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'technologies' => 'nullable|array',
            'github_url' => 'nullable|string|max:255',
            'live_url' => 'nullable|string|max:255',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $project = StudentProject::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'technologies' => $request->input('technologies', []),
            'github_url' => $request->input('github_url'),
            'live_url' => $request->input('live_url'),
        ]);

        return $this->success($project, 'Project added to portfolio.', HttpStatus::HTTP_CREATED);
    }

    /**
     * Add a professional certification.
     */
    public function addCertification(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'nullable|date',
            'credential_url' => 'nullable|string|max:255',
        ]);

        $student = $request->user()->student;
        if (!$student) {
            return $this->error('Student profile required.', HttpStatus::HTTP_FORBIDDEN);
        }

        $cert = StudentCertification::create([
            'university_id' => $student->university_id,
            'student_id' => $student->id,
            'title' => $request->input('title'),
            'issuer' => $request->input('issuer'),
            'issue_date' => $request->input('issue_date'),
            'credential_url' => $request->input('credential_url'),
        ]);

        return $this->success($cert, 'Certification added.', HttpStatus::HTTP_CREATED);
    }

    /**
     * List campus job postings and internship listings.
     */
    public function jobs(Request $request): JsonResponse
    {
        $query = CareerJob::where('is_active', true);

        if ($type = $request->query('job_type')) {
            $query->where('job_type', $type);
        }

        $jobs = $query->latest('deadline')->get();

        return $this->success($jobs, 'Job listings retrieved.');
    }

    /**
     * Generate and download ATS-friendly CV PDF.
     */
    public function downloadCv(Request $request, CvGeneratorService $cvService): Response
    {
        $student = $request->user()->student;
        if (!$student) {
            abort(404, 'Student profile not found.');
        }

        $pdfBinary = $cvService->generate($student);
        $safeMatric = str_replace('/', '_', $student->matric_number);

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"CV_{$safeMatric}.pdf\"",
        ]);
    }
}
