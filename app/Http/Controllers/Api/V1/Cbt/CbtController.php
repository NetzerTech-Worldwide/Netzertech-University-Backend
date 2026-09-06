<?php

namespace App\Http\Controllers\Api\V1\Cbt;

use App\Http\Controllers\Controller;
use App\Models\CbtExam;
use App\Models\CbtQuestion;
use App\Models\CbtStudentSession;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CbtController extends Controller
{
    use ApiResponse;

    /**
     * List all published exams.
     */
    public function index(Request $request): JsonResponse
    {
        $exams = CbtExam::where('is_published', true)
            ->with(['course', 'creator.user'])
            ->withCount('questions')
            ->get();

        return $this->success($exams, 'Available CBT exams retrieved.');
    }

    /**
     * Start an exam session for the student.
     */
    public function startExam(Request $request, int $examId): JsonResponse
    {
        $user = $request->user();
        $student = $user->student;

        if (!$student) {
            return $this->error('Only matriculated students can take CBT examinations.', Response::HTTP_FORBIDDEN);
        }

        $exam = CbtExam::with('questions')->findOrFail($examId);

        // Check if student already has a session
        $session = CbtStudentSession::where('cbt_exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($session && $session->status === 'completed') {
            return $this->error('You have already completed this examination.', Response::HTTP_FORBIDDEN);
        }

        if (!$session) {
            $startedAt = now();
            $expiresAt = now()->addMinutes($exam->duration_minutes);

            $session = CbtStudentSession::create([
                'university_id' => $student->university_id,
                'cbt_exam_id' => $exam->id,
                'student_id' => $student->id,
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
                'status' => 'in_progress',
                'answers' => [],
            ]);
        }

        // Prepare questions for student view (strip correct answers!)
        $questions = $exam->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'question_type' => $q->question_type,
                'options' => $q->options,
                'points' => $q->points,
            ];
        });

        if ($exam->shuffle_questions) {
            $questions = $questions->shuffle()->values();
        }

        return $this->success([
            'session_id' => $session->id,
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'duration_minutes' => $exam->duration_minutes,
                'total_marks' => $exam->total_marks,
            ],
            'started_at' => $session->started_at,
            'expires_at' => $session->expires_at,
            'remaining_seconds' => max(0, $session->expires_at->diffInSeconds(now())),
            'saved_answers' => $session->answers ?? (object)[],
            'questions' => $questions,
        ], 'Exam session started.');
    }

    /**
     * Heartbeat / Autosave answers periodically during exam.
     */
    public function heartbeat(Request $request, int $sessionId): JsonResponse
    {
        $session = CbtStudentSession::findOrFail($sessionId);

        if ($session->status !== 'in_progress') {
            return $this->error('Exam session is no longer active.', Response::HTTP_FORBIDDEN);
        }

        // Check if expired
        if (now()->greaterThan($session->expires_at)) {
            $session->update(['status' => 'timed_out']);
            return $this->error('Exam time has expired.', Response::HTTP_REQUEST_TIMEOUT);
        }

        $answers = $request->input('answers', []);
        $session->update(['answers' => $answers]);

        return $this->success([
            'remaining_seconds' => max(0, $session->expires_at->diffInSeconds(now())),
            'answers_saved' => count($answers),
        ], 'Answers autosaved.');
    }

    /**
     * Final exam submission and instant automated scoring.
     */
    public function submitExam(Request $request, int $sessionId): JsonResponse
    {
        $session = CbtStudentSession::with('exam.questions')->findOrFail($sessionId);

        if ($session->status === 'completed') {
            return $this->error('Exam has already been submitted.', Response::HTTP_FORBIDDEN);
        }

        $submittedAnswers = $request->input('answers', $session->answers ?? []);
        $exam = $session->exam;
        $questions = $exam->questions;

        $totalScoreObtained = 0.00;
        $totalPossibleMarks = 0.00;

        foreach ($questions as $q) {
            $totalPossibleMarks += (float) $q->points;
            $selectedAnswer = $submittedAnswers[$q->id] ?? null;

            if ($selectedAnswer !== null && strtoupper(trim((string)$selectedAnswer)) === strtoupper(trim((string)$q->correct_answer))) {
                $totalScoreObtained += (float) $q->points;
            }
        }

        $percentage = $totalPossibleMarks > 0
            ? round(($totalScoreObtained / $totalPossibleMarks) * 100, 2)
            : 0.00;

        $session->update([
            'answers' => $submittedAnswers,
            'score_obtained' => $totalScoreObtained,
            'percentage' => $percentage,
            'status' => 'completed',
            'submitted_at' => now(),
        ]);

        return $this->success([
            'session_id' => $session->id,
            'exam_title' => $exam->title,
            'score_obtained' => $totalScoreObtained,
            'total_marks' => $totalPossibleMarks,
            'percentage' => $percentage,
            'passed' => $percentage >= (float)$exam->pass_percentage,
            'submitted_at' => $session->submitted_at,
        ], 'Exam completed and scored successfully.');
    }
}
