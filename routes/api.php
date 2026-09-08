<?php

use App\Http\Controllers\Api\V1\Academics\CourseController;
use App\Http\Controllers\Api\V1\Academics\ResultController;
use App\Http\Controllers\Api\V1\Admissions\AdmissionsController;
use App\Http\Controllers\Api\V1\Approvals\ApprovalController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Cbt\CbtController;
use App\Http\Controllers\Api\V1\Facilities\ClinicController;
use App\Http\Controllers\Api\V1\Facilities\HostelController;
use App\Http\Controllers\Api\V1\Facilities\LibraryController;
use App\Http\Controllers\Api\V1\Finance\FinanceController;
use App\Http\Controllers\Api\V1\Identity\DigitalIdController;
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

    // ── Public Webhooks & Security Verification ───────────────────────────
    Route::prefix('finance/webhooks')->group(function () {
        Route::post('/paystack', [FinanceController::class, 'webhookPaystack']);
        Route::post('/remita', [FinanceController::class, 'webhookRemita']);
    });

    Route::get('/verify/id-card/{hash}', [DigitalIdController::class, 'verifyPublic']);

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

        // ── Week 3: Finance, Invoicing & Gateway Payments ──────────────────
        Route::prefix('finance')->group(function () {
            Route::get('/invoices', [FinanceController::class, 'index']);
            Route::post('/invoices/generate', [FinanceController::class, 'generate']);
            Route::get('/invoices/{id}', [FinanceController::class, 'show']);
            Route::post('/pay', [FinanceController::class, 'pay']);
            Route::post('/verify/{reference}', [FinanceController::class, 'verify']);
            Route::get('/receipts/{receiptNo}/pdf', [FinanceController::class, 'downloadReceipt']);
        });

        // ── Week 3: Facilities (Hostels, Clinic & Library) ─────────────────
        Route::prefix('facilities')->group(function () {
            // Hostel Allocation
            Route::get('/hostels', [HostelController::class, 'index']);
            Route::get('/hostels/{id}/rooms', [HostelController::class, 'getRooms']);
            Route::post('/hostels/reserve-bed', [HostelController::class, 'reserveBed']);
            Route::get('/hostels/my-allocation', [HostelController::class, 'myAllocation']);
            Route::post('/hostels/agreement', [HostelController::class, 'signAgreement']);
            Route::get('/hostels/maintenance', [HostelController::class, 'maintenanceTickets']);
            Route::post('/hostels/maintenance', [HostelController::class, 'submitMaintenance']);
            Route::post('/hostels/maintenance/{id}/resolve', [HostelController::class, 'resolveMaintenance']);

            // Health Clinic
            Route::post('/clinic/register', [ClinicController::class, 'register']);
            Route::get('/clinic/card', [ClinicController::class, 'getCard']);
            Route::get('/clinic/appointments', [ClinicController::class, 'appointments']);
            Route::post('/clinic/appointments', [ClinicController::class, 'bookAppointment']);
            Route::post('/clinic/appointments/{id}/diagnose', [ClinicController::class, 'diagnose']);

            // Central Library
            Route::get('/library/catalog', [LibraryController::class, 'catalog']);
            Route::post('/library/reserve', [LibraryController::class, 'reserve']);
            Route::get('/library/my-borrowed', [LibraryController::class, 'myBorrowed']);
            Route::post('/library/loans/{id}/return', [LibraryController::class, 'returnBook']);
        });

        // ── Week 3: Multi-Level Approvals Engine ───────────────────────────
        Route::prefix('approvals')->group(function () {
            Route::get('/requests', [ApprovalController::class, 'index']);
            Route::post('/requests', [ApprovalController::class, 'store']);
            Route::get('/requests/{id}', [ApprovalController::class, 'show']);
            Route::post('/requests/{id}/action', [ApprovalController::class, 'action']);
        });

        // ── Week 3: Tamper-Proof Digital Student ID Card ───────────────────
        Route::prefix('identity')->group(function () {
            Route::get('/card', [DigitalIdController::class, 'getCard']);
            Route::get('/card/pdf', [DigitalIdController::class, 'downloadPdf']);
        });
    });
});
