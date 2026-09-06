<?php

use App\Http\Controllers\Api\V1\Academics\CourseController;
use App\Http\Controllers\Api\V1\Academics\ResultController;
use App\Http\Controllers\Api\V1\Admissions\AdmissionsController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Cbt\CbtController;
use App\Http\Controllers\Api\V1\Lms\LmsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - V1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ── Public Authentication & Admissions & Course Catalog ───────────────
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::prefix('admissions')->group(function () {
        Route::get('/programmes', [AdmissionsController::class, 'getProgrammes']);
        Route::post('/register', [AdmissionsController::class, 'register']);
    });

    Route::get('/courses', [CourseController::class, 'index']);

    // ── Authenticated Routes (Sanctum Protected) ──────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth management
        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
        });

        // Admissions application lifecycle
        Route::prefix('admissions')->group(function () {
            Route::post('/steps/{step}', [AdmissionsController::class, 'saveStep']);
            Route::post('/submit', [AdmissionsController::class, 'submit']);
            Route::get('/status', [AdmissionsController::class, 'getStatus']);
            Route::post('/post-utme/book', [AdmissionsController::class, 'bookPostUtme']);
            Route::post('/offer/accept', [AdmissionsController::class, 'acceptOffer']);
            Route::post('/matriculate', [AdmissionsController::class, 'matriculate']);
        });

        // ── Week 2: Course Registration & SIS ──────────────────────────────
        Route::prefix('courses')->group(function () {
            Route::post('/register', [CourseController::class, 'register']);
            Route::get('/my-registration', [CourseController::class, 'myRegistration']);
            Route::post('/registrations/{id}/approve', [CourseController::class, 'approve']);

            // LMS Materials & Assignments per Course
            Route::get('/{id}/materials', [LmsController::class, 'getMaterials']);
            Route::post('/{id}/materials', [LmsController::class, 'uploadMaterial']);
            Route::get('/{id}/assignments', [LmsController::class, 'getAssignments']);
            Route::post('/{id}/assignments', [LmsController::class, 'createAssignment']);
        });

        // LMS Assignment Actions
        Route::prefix('assignments')->group(function () {
            Route::post('/{id}/submit', [LmsController::class, 'submitAssignment']);
            Route::post('/submissions/{id}/grade', [LmsController::class, 'gradeSubmission']);
        });

        // ── Week 2: Online CBT Engine ──────────────────────────────────────
        Route::prefix('cbt')->group(function () {
            Route::get('/exams', [CbtController::class, 'index']);
            Route::post('/exams/{id}/start', [CbtController::class, 'startExam']);
            Route::post('/sessions/{id}/heartbeat', [CbtController::class, 'heartbeat']);
            Route::post('/sessions/{id}/submit', [CbtController::class, 'submitExam']);
        });

        // ── Week 2: Results & NUC 5.0 CGPA Computation ────────────────────
        Route::prefix('results')->group(function () {
            Route::post('/upload', [ResultController::class, 'uploadScore']);
            Route::get('/semester', [ResultController::class, 'getSemesterResult']);
            Route::get('/transcript', [ResultController::class, 'downloadTranscript']);
        });
    });
});
