# Netzertech University — Laravel Backend Architecture & 30-Day Implementation Roadmap

> **Project Name:** Netzertech University (Novica University Management System)  
> **Backend Framework:** Laravel 11.x (PHP 8.3+)  
> **Database:** PostgreSQL 16 / MySQL 8.0  
> **Cache & Queues:** Redis 7.x + Laravel Horizon + Laravel Reverb (WebSockets)  
> **Target Timeline:** 4 Weeks (30 Calendar Days)  
> **Architecture Pattern:** Domain-Driven Modular Monolith with Action-Service Pattern  

---

## Table of Contents
1. [Executive Summary & Technology Stack](#1-executive-summary--technology-stack)
2. [High-Level System Topology](#2-high-level-system-topology)
3. [Exhaustive Database Schema & Entity Relationships](#3-exhaustive-database-schema--entity-relationships)
4. [Critical Business Logic Engines](#4-critical-business-logic-engines)
5. [Complete REST API Contract & Route Taxonomy](#5-complete-rest-api-contract--route-taxonomy)
6. [Docker & Production Deployment Blueprint](#6-docker--production-deployment-blueprint)
7. [Day-by-Day Interactive 30-Day Action Plan](#7-day-by-day-interactive-30-day-action-plan)
8. [Quality Assurance & Security Checklist](#8-quality-assurance--security-checklist)

---

## 1. Executive Summary & Technology Stack

The Netzertech University platform is an enterprise-grade university management system combining:
* **Student Information System (SIS):** Admissions, bio-data, course registration, timetable, transcript & results.
* **Learning Management System (LMS) & CBT:** Handouts, assignments, online testing with instant grading.
* **ERP & Campus Living:** Invoicing & payment gateways (Paystack/Remita), hostel bed-space allocation, university clinic/hospital records, library book loan tracking.
* **Workflow Automation & Multi-Tier Approvals:** Dynamic hierarchical approval chains for academic requests.
* **Postgraduate School (SPS):** Thesis milestone tracker, research proposal workflow, early warning system.
* **Student Success:** Context-aware AI Academic Advisor, Digital ID card with tamper-proof HMAC QR codes, CV generator, and career portal.

### Core Stack Specifications

| Component | Technology | Role & Purpose |
| :--- | :--- | :--- |
| **Framework** | Laravel 11.x | Modern PHP backend with native queue workers, scheduling, and API auth. |
| **Language** | PHP 8.3+ | Typed properties, enums, JIT optimization, readonly classes. |
| **Primary Database** | PostgreSQL 16 (or MySQL 8.0) | Relational integrity, foreign key constraints, JSON querying, transactions. |
| **Cache & Distributed Locks** | Redis 7.x | High-speed cache, rate-limiting, distributed mutex for bed/course booking. |
| **Real-time WebSockets** | Laravel Reverb | Real-time chat for study groups, instant approval alerts, and notifications. |
| **Queue Management** | Laravel Horizon | Redis-backed asynchronous worker management for email and PDF jobs. |
| **Object Storage** | AWS S3 / Cloudflare R2 | Encrypted cloud storage for student photos, documents, and lecture files. |
| **PDF Engine** | Spatie Browsershot / DomPDF | Server-side rendering of official transcripts, ID cards, and receipts. |
| **Search Engine** | Laravel Scout + Meilisearch | Fast full-text search across library books, course lists, and student directories. |
| **AI Engine** | OpenAI / Gemini API (via Laravel HTTP) | Contextual academic guidance and degree progress advisor chatbot. |

---

## 2. High-Level System Topology

```
+-------------------------------------------------------------------------+
|                              CLIENT APPS                                |
|  - React 18 Web Portal (Vite + Tailwind CSS + Shadcn UI)                |
|  - Progressive Web App (PWA) / Mobile Apps                              |
+------------------------------------+------------------------------------+
                                     | HTTPS / JSON REST
                                     v
+------------------------------------+------------------------------------+
|                         NGINX REVERSE PROXY                             |
|  - SSL Termination  - Rate Limiting  - WAF / Security Headers           |
+------------------------------------+------------------------------------+
                                     |
                                     v
+-------------------------------------------------------------------------+
|                    LARAVEL 11 MODULAR MONOLITH CORE                     |
|                                                                         |
|  +--------------------+  +--------------------+  +--------------------+ |
|  | Auth & Spatie RBAC |  | Admissions Engine  |  | Academic SIS       | |
|  +--------------------+  +--------------------+  +--------------------+ |
|  +--------------------+  +--------------------+  +--------------------+ |
|  | LMS & Online CBT   |  | Finance & Gateways |  | Approvals Engine   | |
|  +--------------------+  +--------------------+  +--------------------+ |
|  +--------------------+  +--------------------+  +--------------------+ |
|  | Facilities & Clinic|  | Postgraduate (SPS) |  | AI Academic Advisor| |
|  +--------------------+  +--------------------+  +--------------------+ |
+------------------+------------------+------------------+----------------+
                   |                  |                  |
                   v                  v                  v
+----------------------+  +----------------------+  +---------------------+
| PostgreSQL 16 Primary|  | Redis 7 (Cache/Locks)|  | Object Storage (S3) |
+----------------------+  +----------------------+  +---------------------+
```

---

## 3. Exhaustive Database Schema & Entity Relationships

### 3.1 Identity, Users & Roles
* `users`: `id (BIGINT/UUID PK)`, `name`, `email (UNIQUE)`, `phone (UNIQUE)`, `password`, `user_type (ENUM: 'applicant', 'student', 'postgraduate', 'staff', 'admin')`, `is_active (BOOLEAN)`, `avatar_url`, `created_at`, `updated_at`.
* `roles` & `permissions`: Standard Spatie RBAC tables.
* `students`: `id (PK)`, `user_id (FK users)`, `matric_number (UNIQUE, VARCHAR(30))`, `jamb_reg_no (NULLABLE, VARCHAR(30))`, `faculty_id (FK)`, `department_id (FK)`, `programme_id (FK)`, `level (ENUM: '100L', '200L', '300L', '400L', '500L', 'PG')`, `academic_session (VARCHAR(15))`, `current_semester (ENUM: 'first', 'second')`, `entry_mode (ENUM: 'UTME', 'Direct Entry', 'PG')`, `cgpa (DECIMAL(3,2), DEFAULT 0.00)`, `standing (ENUM: 'Good', 'Probation', 'Withdrawn')`, `digital_id_token (VARCHAR(64), UNIQUE)`, `created_at`.
* `staff`: `id (PK)`, `user_id (FK users)`, `staff_no (UNIQUE, VARCHAR(30))`, `department_id (FK)`, `faculty_id (FK)`, `designation (VARCHAR(100))`, `title (VARCHAR(20))`, `rank (ENUM: 'Lecturer', 'HOD', 'Dean', 'Registrar', 'Bursar', 'VC', 'Examinations Officer', 'Hostel Master', 'Medical Officer')`, `color_code (VARCHAR(10))`, `created_at`.
* `student_profiles`: `id (PK)`, `student_id (FK)`, `dob (DATE)`, `gender (ENUM: 'M', 'F')`, `state_of_origin (VARCHAR(50))`, `lga (VARCHAR(50))`, `nationality (VARCHAR(50))`, `address (TEXT)`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `blood_group`, `genotype`, `allergies (TEXT)`, `medical_conditions (TEXT)`.

### 3.2 Admissions & Screening
* `admissions_applications`: `id (PK)`, `user_id (FK)`, `application_no (UNIQUE)`, `first_choice_programme_id (FK)`, `second_choice_programme_id (FK)`, `status (ENUM: 'draft', 'submitted', 'under_review', 'invited_for_screening', 'admitted', 'rejected', 'supplementary')`, `app_fee_paid (BOOLEAN)`, `screening_score (DECIMAL(5,2))`, `offer_accepted (BOOLEAN)`, `acceptance_fee_paid (BOOLEAN)`, `submitted_at`, `created_at`.
* `jamb_records`: `id (PK)`, `application_id (FK)`, `reg_number`, `score (INT)`, `year (INT)`, `verified (BOOLEAN)`.
* `olevel_results`: `id (PK)`, `application_id (FK)`, `sitting_number (TINYINT: 1, 2)`, `exam_type (ENUM: 'WAEC', 'NECO', 'NABTEB')`, `exam_year (INT)`, `exam_number`, `centre_number`, `subjects (JSONB)`.
* `post_utme_slots`: `id (PK)`, `application_id (FK)`, `exam_date (DATE)`, `exam_time (TIME)`, `venue`, `seat_no`, `is_attended (BOOLEAN)`.

### 3.3 Academic SIS, Courses & Results
* `faculties`: `id (PK)`, `name`, `code`, `dean_staff_id (FK staff)`.
* `departments`: `id (PK)`, `faculty_id (FK)`, `name`, `code`, `hod_staff_id (FK staff)`.
* `programmes`: `id (PK)`, `department_id (FK)`, `name`, `degree (ENUM: 'B.Sc.', 'B.Eng.', 'B.A.', 'M.Sc.', 'Ph.D.', 'PGD')`, `duration_years (INT)`.
* `courses`: `id (PK)`, `department_id (FK)`, `code (VARCHAR(15))`, `title`, `credit_units (INT)`, `level`, `semester`, `is_elective (BOOLEAN)`, `lecturer_staff_id (FK staff)`, `prerequisites (JSONB)`.
* `course_registrations`: `id (PK)`, `student_id (FK)`, `course_id (FK)`, `session`, `semester`, `status (ENUM: 'draft', 'submitted', 'approved', 'rejected')`, `approved_by_advisor_at`, `approved_by_hod_at`.
* `student_course_grades`: `id (PK)`, `student_id (FK)`, `course_id (FK)`, `session`, `semester`, `ca_score (DECIMAL(5,2))`, `exam_score (DECIMAL(5,2))`, `total_score (DECIMAL(5,2))`, `grade_letter (VARCHAR(2))`, `grade_point (DECIMAL(3,2))`, `credit_points (DECIMAL(4,2))`, `is_published (BOOLEAN)`.
* `student_semester_gpa`: `id (PK)`, `student_id (FK)`, `session`, `semester`, `total_units_registered (INT)`, `total_units_passed (INT)`, `total_grade_points (DECIMAL(6,2))`, `gpa (DECIMAL(3,2))`, `cgpa (DECIMAL(3,2))`, `standing (VARCHAR(50))`.

### 3.4 LMS & Online Computer-Based Testing (CBT)
* `course_materials`: `id (PK)`, `course_id (FK)`, `title`, `file_url`, `file_size_bytes`, `uploaded_by (FK staff)`, `week_number (INT)`.
* `assignments`: `id (PK)`, `course_id (FK)`, `title`, `description`, `max_marks`, `due_date (DATETIME)`, `allow_late_submission (BOOLEAN)`.
* `assignment_submissions`: `id (PK)`, `assignment_id (FK)`, `student_id (FK)`, `file_url`, `text_content`, `submitted_at`, `status (ENUM: 'submitted', 'graded', 'late')`, `score`, `feedback`, `graded_by (FK staff)`.
* `cbt_exams`: `id (PK)`, `course_id (FK)`, `title`, `duration_minutes (INT)`, `total_questions (INT)`, `pass_percentage`, `start_time`, `end_time`, `status (ENUM: 'draft', 'active', 'concluded')`.
* `cbt_questions`: `id (PK)`, `cbt_exam_id (FK)`, `question_text`, `question_type (ENUM: 'single_choice', 'multiple_choice', 'true_false')`, `options (JSONB)`, `correct_answer (JSONB)`, `marks`.
* `cbt_student_sessions`: `id (PK)`, `cbt_exam_id (FK)`, `student_id (FK)`, `started_at`, `submitted_at`, `score_obtained`, `status (ENUM: 'not_started', 'in_progress', 'completed')`, `answers (JSONB)`.

### 3.5 Finance, Invoicing & Payments
* `invoices`: `id (PK)`, `invoice_number (UNIQUE)`, `user_id (FK users)`, `session`, `total_amount (DECIMAL(12,2))`, `amount_paid (DECIMAL(12,2))`, `status (ENUM: 'unpaid', 'partially_paid', 'paid', 'cancelled')`, `due_date (DATE)`.
* `invoice_items`: `id (PK)`, `invoice_id (FK)`, `fee_type (ENUM: 'tuition', 'acceptance', 'hostel', 'faculty_levy', 'sug_levy', 'clinic_registration', 'cbt_fee', 'custom')`, `description`, `amount`, `is_paid (BOOLEAN)`.
* `payments`: `id (PK)`, `transaction_reference (UNIQUE)`, `invoice_id (FK)`, `amount`, `gateway (ENUM: 'paystack', 'flutterwave', 'remita', 'bank_transfer')`, `gateway_reference`, `status (ENUM: 'pending', 'successful', 'failed', 'refunded')`, `receipt_number (UNIQUE)`, `raw_webhook_payload (JSONB)`, `paid_at (DATETIME)`.

### 3.6 Multi-Level Approvals Engine
* `approval_requests`: `id (PK)`, `request_ref (UNIQUE)`, `student_id (FK)`, `type (ENUM: 'course_form_signing', 'transcript_request', 'deferral_of_exams', 'change_of_course', 'medical_leave', 'custom')`, `title`, `purpose`, `required_approvals (INT)`, `current_level (INT)`, `status (ENUM: 'pending', 'in_review', 'approved', 'rejected')`, `supporting_doc_url`.
* `approver_slots`: `id (PK)`, `approval_request_id (FK)`, `staff_id (FK staff)`, `sequence_order (INT)`, `status (ENUM: 'pending', 'approved', 'rejected')`, `comment`, `acted_at`.

### 3.7 Facilities: Hostel, Clinic & Library
* `hostels`: `id (PK)`, `name`, `gender (ENUM: 'male', 'female', 'mixed')`, `campus_location`, `master_staff_id (FK staff)`.
* `hostel_rooms`: `id (PK)`, `hostel_id (FK)`, `block_name`, `room_number`, `floor`, `room_type (ENUM: 'single', 'double', 'quad')`, `capacity (INT)`, `allocated_count (INT)`.
* `bed_spaces`: `id (PK)`, `room_id (FK)`, `bed_label`, `is_occupied (BOOLEAN)`, `lock_token`, `locked_until (DATETIME)`.
* `hostel_allocations`: `id (PK)`, `student_id (FK)`, `bed_space_id (FK)`, `session`, `semester`, `allocation_ref (UNIQUE)`, `check_in_date`, `check_out_date`, `status (ENUM: 'allocated', 'confirmed', 'vacated')`, `agreement_signed (BOOLEAN)`.
* `clinic_registrations`: `id (PK)`, `student_id (FK, UNIQUE)`, `hospital_id_no (UNIQUE)`, `blood_group`, `genotype`, `allergies`, `medical_history`, `issued_date`.
* `clinic_appointments`: `id (PK)`, `student_id (FK)`, `doctor_staff_id (FK staff)`, `appointment_date`, `appointment_time`, `symptoms`, `diagnosis`, `prescription`, `status`.
* `library_items`: `id (PK)`, `isbn`, `title`, `author`, `category`, `total_copies`, `available_copies`, `shelf_location`, `is_digital (BOOLEAN)`, `digital_download_url`.
* `library_borrow_records`: `id (PK)`, `student_id (FK)`, `library_item_id (FK)`, `borrow_date`, `due_date`, `return_date`, `fine_amount`, `status`.

### 3.8 Postgraduate (SPS) Lifecycle
* `pg_student_profiles`: `id (PK)`, `student_id (FK, UNIQUE)`, `research_title`, `primary_supervisor_staff_id (FK staff)`, `co_supervisor_staff_id (FK staff)`, `current_stage (ENUM: 'coursework', 'proposal_defense', 'internal_defense', 'external_defense', 'senate_clearance', 'graduated')`, `expected_graduation_date`, `risk_score (ENUM: 'low', 'medium', 'high', 'critical')`.
* `pg_proposals`: `id (PK)`, `student_id (FK)`, `title`, `abstract`, `document_url`, `status`, `feedback_history (JSONB)`.
* `pg_thesis_milestones`: `id (PK)`, `student_id (FK)`, `milestone_name`, `due_date`, `completion_date`, `status`.
* `pg_supervision_logs`: `id (PK)`, `student_id (FK)`, `staff_id (FK)`, `meeting_date`, `summary_notes`, `next_deliverables`, `approved_by_supervisor`.

---

## 4. Critical Business Logic Engines

### 4.1 NUC 5.0 CGPA Computation Engine
```php
<?php
namespace App\Services\Academic;

use App\Models\Student;
use App\Models\StudentCourseGrade;
use App\Models\StudentSemesterGpa;
use Illuminate\Support\Facades\DB;

class GpaCalculatorService
{
    private const GRADE_SCALE = [
        ['min' => 70, 'max' => 100, 'letter' => 'A', 'points' => 5.0],
        ['min' => 60, 'max' => 69,  'letter' => 'B', 'points' => 4.0],
        ['min' => 50, 'max' => 59,  'letter' => 'C', 'points' => 3.0],
        ['min' => 45, 'max' => 49,  'letter' => 'D', 'points' => 2.0],
        ['min' => 0,  'max' => 44,  'letter' => 'F', 'points' => 0.0],
    ];

    public function determineGrade(float $totalScore): array
    {
        foreach (self::GRADE_SCALE as $tier) {
            if ($totalScore >= $tier['min'] && $totalScore <= $tier['max']) {
                return ['letter' => $tier['letter'], 'points' => $tier['points']];
            }
        }
        return ['letter' => 'F', 'points' => 0.0];
    }

    public function calculateSemesterAndCumulativeGpa(Student $student, string $session, string $semester): StudentSemesterGpa
    {
        return DB::transaction(function () use ($student, $session, $semester) {
            $currentGrades = StudentCourseGrade::where('student_id', $student->id)
                ->where('session', $session)
                ->where('semester', $semester)
                ->with('course')
                ->get();

            $totalSemUnits = 0;
            $totalSemPassedUnits = 0;
            $totalSemQualityPoints = 0;

            foreach ($currentGrades as $grade) {
                $units = $grade->course->credit_units;
                $totalSemUnits += $units;
                if ($grade->grade_point > 0) {
                    $totalSemPassedUnits += $units;
                }
                $totalSemQualityPoints += ($grade->grade_point * $units);
            }

            $semesterGpa = $totalSemUnits > 0 ? round($totalSemQualityPoints / $totalSemUnits, 2) : 0.00;

            $allGrades = StudentCourseGrade::where('student_id', $student->id)->with('course')->get();
            $totalCumUnits = 0;
            $totalCumQualityPoints = 0;

            foreach ($allGrades as $histGrade) {
                $units = $histGrade->course->credit_units;
                $totalCumUnits += $units;
                $totalCumQualityPoints += ($histGrade->grade_point * $units);
            }

            $cumulativeCgpa = $totalCumUnits > 0 ? round($totalCumQualityPoints / $totalCumUnits, 2) : 0.00;

            $record = StudentSemesterGpa::updateOrCreate(
                ['student_id' => $student->id, 'session' => $session, 'semester' => $semester],
                [
                    'total_units_registered' => $totalSemUnits,
                    'total_units_passed' => $totalSemPassedUnits,
                    'total_grade_points' => $totalSemQualityPoints,
                    'gpa' => $semesterGpa,
                    'cgpa' => $cumulativeCgpa,
                    'standing' => $this->determineAcademicStanding($cumulativeCgpa),
                ]
            );

            $student->update(['cgpa' => $cumulativeCgpa, 'standing' => $record->standing]);
            return $record;
        });
    }

    private function determineAcademicStanding(float $cgpa): string
    {
        if ($cgpa >= 4.50) return 'First Class Honours';
        if ($cgpa >= 3.50) return 'Second Class Upper';
        if ($cgpa >= 2.40) return 'Second Class Lower';
        if ($cgpa >= 1.50) return 'Third Class';
        if ($cgpa >= 1.00) return 'Pass';
        return 'Probation / Withdrawal';
    }
}
```

---

## 5. Complete REST API Contract & Route Taxonomy

All routes are versioned under `/api/v1` and use Bearer Token authorization.

```
API Route Architecture:
├── /api/v1/auth
│   ├── POST /login
│   ├── POST /logout
│   ├── POST /refresh-token
│   ├── POST /change-password
│   └── GET  /me
├── /api/v1/admissions
│   ├── POST /register
│   ├── POST /application/steps/{step}
│   ├── POST /application/submit
│   ├── GET  /status
│   ├── POST /post-utme/book
│   ├── POST /offer/accept
│   └── POST /matriculate
├── /api/v1/academic
│   ├── GET  /courses
│   ├── POST /courses/register
│   ├── GET  /courses/registered
│   ├── GET  /timetable
│   ├── GET  /results
│   └── GET  /transcript/pdf
├── /api/v1/lms
│   ├── GET  /courses/{id}/materials
│   ├── POST /courses/{id}/materials
│   ├── GET  /courses/{id}/assignments
│   ├── POST /assignments/{id}/submit
│   └── POST /assignments/submissions/{id}/grade
├── /api/v1/cbt
│   ├── GET  /catalog
│   ├── POST /exams/{id}/start
│   ├── POST /exams/{id}/heartbeat-answer
│   └── POST /exams/{id}/submit
├── /api/v1/finance
│   ├── GET  /invoices
│   ├── POST /pay
│   ├── POST /verify/{reference}
│   ├── POST /webhooks/paystack
│   └── GET  /receipts/{receiptNo}/pdf
├── /api/v1/approvals
│   ├── GET  /requests
│   ├── POST /requests
│   └── POST /requests/{id}/action
├── /api/v1/facilities
│   ├── GET  /hostels
│   ├── POST /hostels/reserve-bed
│   ├── POST /clinic/register
│   ├── GET  /clinic/card
│   ├── GET  /library/catalog
│   └── POST /library/reserve
├── /api/v1/postgraduate
│   ├── GET  /dashboard
│   ├── POST /proposals
│   ├── POST /thesis/chapter
│   └── GET  /early-warning
└── /api/v1/ai
    └── POST /advisor/chat
```

---

## 6. Docker & Production Deployment Blueprint

Save this file as `docker-compose.yml` in your project root:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: netzertech_api
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    environment:
      - APP_ENV=production
      - DB_CONNECTION=pgsql
      - DB_HOST=postgres
      - REDIS_HOST=redis
    depends_on:
      - postgres
      - redis
    networks:
      - netzertech_network

  nginx:
    image: nginx:alpine
    container_name: netzertech_nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - netzertech_network

  postgres:
    image: postgres:16-alpine
    container_name: netzertech_db
    restart: unless-stopped
    environment:
      POSTGRES_DB: ${DB_DATABASE:-netzertech_university}
      POSTGRES_USER: ${DB_USERNAME:-postgres}
      POSTGRES_PASSWORD: ${DB_PASSWORD:-secret}
    volumes:
      - pgdata:/var/lib/postgresql/data
    networks:
      - netzertech_network

  redis:
    image: redis:7-alpine
    container_name: netzertech_redis
    restart: unless-stopped
    command: ["redis-server", "--appendonly", "yes"]
    volumes:
      - redisdata:/data
    networks:
      - netzertech_network

  horizon:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: netzertech_horizon
    restart: unless-stopped
    working_dir: /var/www
    command: php artisan horizon
    depends_on:
      - app
      - redis
    networks:
      - netzertech_network

  reverb:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: netzertech_reverb
    restart: unless-stopped
    working_dir: /var/www
    command: php artisan reverb:start --host=0.0.0.0 --port=8080
    ports:
      - "8080:8080"
    depends_on:
      - app
    networks:
      - netzertech_network

networks:
  netzertech_network:
    driver: bridge

volumes:
  pgdata:
  redisdata:
```

---

## 7. Day-by-Day Interactive 30-Day Action Plan

Use this checklist to follow up and track your daily implementation progress.

### 🚀 WEEK 1: Project Setup, Identity (RBAC) & Admissions Portal

- [ ] **Day 1: Project Initialization & Environment Setup**
  - [ ] Initialize Laravel 11 project with PHP 8.3 (`composer create-project laravel/laravel netzertech-backend`).
  - [ ] Configure `.env` for PostgreSQL, Redis, Mail, and S3.
  - [ ] Install packages: `laravel/sanctum`, `spatie/laravel-permission`, `laravel/horizon`, `laravel/reverb`, `barryvdh/laravel-dompdf`.
  - [ ] Setup standard JSON API Response Wrapper & Exception Handler.

- [ ] **Day 2: Core User Models, RBAC & Authentication**
  - [ ] Create migrations: `users`, `roles`, `permissions`, `students`, `staff`.
  - [ ] Implement Auth Controllers: `LoginController`, `LogoutController`, `PasswordResetController`.
  - [ ] Seed default SuperAdmin, HOD, Dean, Lecturer, Student, and Applicant roles.
  - [ ] Build `/api/v1/auth/me` returning current user profile, permissions, and active session.

- [ ] **Day 3: Pre-Admission Application Step Form**
  - [ ] Create migrations: `admissions_applications`, `jamb_records`, `olevel_results`.
  - [ ] Build endpoints for Personal Info, JAMB verification, O'Level results (Sitting 1 & 2), Programme choices.
  - [ ] Implement secure document upload to AWS S3/Cloudflare R2.

- [ ] **Day 4: Admissions Screening & Post-UTME Scheduling**
  - [ ] Create `post_utme_slots` table and booking endpoints.
  - [ ] Build Applicant Dashboard API and status checker (`/admissions/status`).
  - [ ] Implement administrative screening review & score recording endpoints.

- [ ] **Day 5: Offer Letter Issuance & Acceptance Payment**
  - [ ] Build automated Offer Letter PDF generator using DomPDF/Browsershot.
  - [ ] Integrate acceptance fee payment initialization and verification.
  - [ ] Implement student offer acceptance endpoint.

- [ ] **Day 6: Matriculation & Student Transition Service**
  - [ ] Implement `MatricNumberGenerator` (`NVU/{YEAR}/{DEPT}/{SERIAL}`).
  - [ ] Build service converting admitted `Applicant` into active `Student` account.
  - [ ] Build initial student clearance and onboarding checklist API.

- [ ] **Day 7: Week 1 Integration Testing & Frontend Alignment**
  - [ ] Write Pest/PHPUnit integration tests for the full admission lifecycle.
  - [ ] Verify React Admissions portal endpoints against frontend components.

---

### 📚 WEEK 2: Academic SIS, Course Registration, LMS & Online CBT Engine

- [ ] **Day 8: Academic Structure & Course Registration**
  - [ ] Create migrations: `faculties`, `departments`, `programmes`, `courses`, `course_registrations`.
  - [ ] Build course catalog API with filtering by Level, Semester, and Department.
  - [ ] Implement `CourseRegistrationService` with prerequisite and credit unit validation.

- [ ] **Day 9: Lecture & Examination Timetable Engine**
  - [ ] Create timetable migrations with day, time slot, venue, lecturer relations.
  - [ ] Implement timetable query endpoint with lecture clash detection logic.
  - [ ] Build continuous assessment (CA) and final exam schedule endpoints.

- [ ] **Day 10: LMS Course Materials & Lecture Notes**
  - [ ] Create `course_materials` table with weekly organization.
  - [ ] Build file upload/download endpoints with secure signed URLs.
  - [ ] Implement course discussion threads and reply endpoints.

- [ ] **Day 11: Assignment Management & Submission**
  - [ ] Create `assignments` and `assignment_submissions` tables.
  - [ ] Build student assignment submission API with countdown deadline enforcement.
  - [ ] Build lecturer grading, score entry, and feedback endpoints.

- [ ] **Day 12: Computer-Based Testing (CBT) Engine — Setup & Start**
  - [ ] Create `cbt_exams`, `cbt_questions`, `cbt_student_sessions` tables.
  - [ ] Build exam catalog API and student exam launch endpoint with randomized question sets.
  - [ ] Implement secure time-window token verification.

- [ ] **Day 13: CBT Autosave Heartbeat & Instant Scoring**
  - [ ] Implement realtime autosave heartbeat API per answered question.
  - [ ] Build exam auto-submission on timer expiry and instant auto-scoring engine.
  - [ ] Generate student exam result summary.

- [ ] **Day 14: Grading System & NUC 5.0 CGPA Calculator**
  - [ ] Implement `GpaCalculatorService` (A=5.0, B=4.0, C=3.0, D=2.0, F=0.0).
  - [ ] Build semester result view endpoint with GPA trend history.
  - [ ] Implement official academic transcript PDF generation.
  - [ ] Run full unit test suite for GPA calculations.

---

### 💳 WEEK 3: Multi-Level Approvals, Facilities (Hostel & Clinic) & Finance

- [ ] **Day 15: Invoicing, Tuition & Installment Schedules**
  - [ ] Create `invoices`, `invoice_items`, `payments` tables.
  - [ ] Build fee breakdown endpoint (Tuition, Acceptance, Hostel, Faculty Levies).
  - [ ] Implement installment schedule generator (1st, 2nd, 3rd installments).

- [ ] **Day 16: Payment Gateways (Paystack / Remita) & Webhooks**
  - [ ] Integrate Paystack & Remita transaction initialization.
  - [ ] Implement webhook listener with HMAC SHA-512 signature validation.
  - [ ] Build automated payment receipt PDF generation.

- [ ] **Day 17: Multi-Level Dynamic Approvals Engine**
  - [ ] Create `approval_requests` and `approver_slots` tables.
  - [ ] Implement approval state machine (Course Form, Deferral, Transcript, Medical Leave).
  - [ ] Setup Laravel Reverb WebSocket broadcasts and email alerts for approvers.

- [ ] **Day 18: Hostel Bed-Space Allocation (Redis Mutex)**
  - [ ] Create `hostels`, `hostel_rooms`, `bed_spaces`, `hostel_allocations`.
  - [ ] Implement atomic Redis distributed lock to prevent double-booking.
  - [ ] Build room selection, rules agreement confirmation, and allocation letter PDF.

- [ ] **Day 19: Hostel Maintenance & University Health Clinic**
  - [ ] Build hostel maintenance ticketing endpoints with priority levels.
  - [ ] Create `clinic_registrations` and `clinic_appointments` tables.
  - [ ] Build digital hospital card API (Blood group, genotype, emergency contacts).

- [ ] **Day 20: Tamper-Proof Digital ID & Library System**
  - [ ] Implement HMAC-signed QR Code generation for student ID cards.
  - [ ] Build public verification endpoint (`/public/verify-id/{token}`).
  - [ ] Implement Library catalog search (Meilisearch) and book reservation endpoints.

- [ ] **Day 21: Week 3 Integration Testing & Review**
  - [ ] Write integration tests for payment webhooks, approvals workflow, and mutex locks.
  - [ ] Connect React Finance, Approvals, Hostel, and Clinic pages to backend.

---

### 🎓 WEEK 4: Postgraduate (SPS), AI Advisor, QA & Production Deployment

- [ ] **Day 22: Postgraduate (SPS) Programme & Research Proposals**
  - [ ] Create `pg_student_profiles`, `pg_proposals`, `pg_thesis_milestones`.
  - [ ] Build research proposal submission, supervisor allocation, and review endpoints.

- [ ] **Day 23: Thesis Milestones, Supervision Logs & Early Warning Engine**
  - [ ] Build chapter-by-chapter thesis submission and defense scheduling APIs.
  - [ ] Build supervision meeting logs API.
  - [ ] Implement SPS Early Warning analytics service (identifying at-risk delayed students).

- [ ] **Day 24: AI Academic Advisor Integration**
  - [ ] Create `AIAdvisorService` integrating OpenAI / Gemini API.
  - [ ] Engineer contextual prompts with student GPA, registered courses, and university regulations.
  - [ ] Build interactive advisor chat endpoint with conversation history.

- [ ] **Day 25: Career Portal, CV Generator & Study Groups**
  - [ ] Build student skills tracker, project showcase, and job board APIs.
  - [ ] Implement automated CV PDF generator summarizing student profile.
  - [ ] Build study group chat and shared file repository with Laravel Reverb.

- [ ] **Day 26: Full End-to-End Frontend Integration Audit**
  - [ ] Audit every React route in the frontend repo against the Laravel API.
  - [ ] Resolve any CORS, pagination, or token renewal discrepancies.

- [ ] **Day 27: Performance Tuning & Redis Optimization**
  - [ ] Add composite database indexes on foreign keys, matric numbers, and sessions.
  - [ ] Configure Redis cache for course catalogs, fee schedules, and timetables.
  - [ ] Eliminate N+1 query bottlenecks using Laravel Eager Loading (`with()`).

- [ ] **Day 28: Security Hardening & Load Testing**
  - [ ] Run load tests with k6 (simulating 1,000+ concurrent CBT and Hostel requests).
  - [ ] Perform OWASP security checks (SQLi, IDOR, XSS, rate limiting).

- [ ] **Day 29: Production Staging Deployment & UAT**
  - [ ] Deploy Docker stack to Linux VPS / AWS with Nginx and SSL certificates.
  - [ ] Configure Laravel Horizon daemon, Reverb WebSocket daemon, and Cron Scheduler.
  - [ ] Execute User Acceptance Testing (UAT) with stakeholder demo.

- [ ] **Day 30: Final Handover, Postman Collection & Project Sign-Off**
  - [ ] Export OpenAPI / Postman Collection with documentation.
  - [ ] Final production verification and contract delivery.

---

## 8. Quality Assurance & Security Checklist

* [ ] **API Security:** All private endpoints protected by `auth:sanctum` and role middleware.
* [ ] **Data Integrity:** All multi-table updates (GPA calculation, payments, bed allocation) wrapped in `DB::transaction()`.
* [ ] **Concurrency:** Bed allocation and course seat booking guarded with Redis atomic locks.
* [ ] **Financial Auditing:** Payment webhooks verified via HMAC-SHA512 with duplicate reference checks.
* [ ] **Rate Limiting:** Login throttled to 5 req/min; public APIs throttled to 60 req/min.
* [ ] **File Security:** Uploaded files validated for MIME types, renamed, and stored with private S3 permissions.
* [ ] **Backup Strategy:** Automated daily PostgreSQL database dump and S3 sync cron configured.
