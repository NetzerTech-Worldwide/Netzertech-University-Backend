# Netzertech University Portal Platform — Backend API (SaaS Core)

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![PostgreSQL 18](https://img.shields.io/badge/PostgreSQL-18.x-336791.svg)](https://www.postgresql.org)
[![Tests Passing](https://img.shields.io/badge/PHPUnit-19%20Passed%20(114%20Assertions)-success.svg)](tests)
[![Multi-Tenant Architecture](https://img.shields.io/badge/Architecture-Multi--Tenant%20SaaS-emerald.svg)](#multi-tenant-architecture)

The enterprise backend API engine powering the **Netzertech University Platform (Novica & Multi-Tenant Tertiary Institutions)**. Built with Laravel 11, PostgreSQL 18, Sanctum API security, and Spatie RBAC.

---

## Table of Contents
- [Architecture & Multi-Tenancy](#multi-tenant-architecture)
- [Key Modules Completed](#key-modules-completed)
  - [1. Authentication & Multi-Tenancy](#1-unified-authentication--multi-tenancy)
  - [2. Admissions & Automated Matriculation](#2-admissions--automated-matriculation)
  - [3. Academic SIS & Course Registration](#3-academic-sis--course-registration)
  - [4. Learning Management System (LMS)](#4-learning-management-system-lms)
  - [5. Online CBT Examination Engine](#5-online-cbt-examination-engine)
  - [6. NUC 5.0 CGPA & Transcript Generator](#6-nuc-50-cgpa-computation--transcripts)
- [Database Structure (37 Tables)](#database-structure)
- [Installation & Quickstart](#installation--quickstart)
- [API Testing (Thunder Client & Postman)](#api-testing)
- [Automated Test Suite](#automated-test-suite)
- [Monetization & Payment Split Architecture](#monetization--split-payment-engine)

---

## Multi-Tenant Architecture

The platform operates as a true **Scoped Multi-Tenant SaaS Engine**. A single deployment serves multiple tertiary institutions simultaneously while guaranteeing complete data isolation:

```
                          STUDENTS & STAFF ACCESS
                                     │
         ┌───────────────────────────┼───────────────────────────┐
         ▼                           ▼                           ▼
novica.netzertech.com       apex.netzertech.com         unilag.netzertech.com
         │                           │                           │
         └───────────────────────────┼───────────────────────────┘
                                     ▼
                       LARAVEL 11 MULTI-TENANT CORE
                    (Auto-Resolves University Tenant)
                                     │
         ┌───────────────────────────┼───────────────────────────┐
         ▼                           ▼                           ▼
[TENANT 1: Novica]          [TENANT 2: Apex]            [TENANT 3: Unilag]
• Isolated Students/Staff   • Isolated Students/Staff   • Isolated Students/Staff
• Custom Logo & Primary     • Custom Logo & Primary     • Custom Logo & Primary
• Separate Bank Payouts     • Separate Bank Payouts     • Separate Bank Payouts
```

* **Tenant Resolution:** Automatically detected via **Subdomain** (`novica.netzertech.com`), **Custom Domain** (`portal.novica.edu.ng`), or HTTP Header (`X-University-Code: NVU`).
* **Data Isolation:** Enforced at the database ORM layer via `TenantScope` and `BelongsToTenant`. University A can never view or modify University B's records.

---

## Key Modules Completed

### 1. Unified Authentication & Multi-Tenancy
* **Multi-Identifier Login:** Authenticate using **Email Address**, **Student Matriculation Number** (e.g. `NVU/2021/CSC/001`), or **Applicant Reference Code** (`APP/NVU/2026/00142`).
* **Role-Based Access Control (RBAC):** Distinct permissions for `super_admin`, `dean`, `hod`, `lecturer`, `bursar`, `registrar`, `exam_officer`, `student`, and `applicant`.
* **Cross-Tenant Blocking:** Prevents students or staff of University A from authenticating into University B's portal.

### 2. Admissions & Automated Matriculation
* **Multi-Step Application:** Captures Personal Bio-data, JAMB score verification, single/double O-Level sittings (WAEC/NECO), degree preferences, and document uploads.
* **Post-UTME Screening:** Automated scheduling with date, time, venue, and seat number allocation.
* **Digital Matriculation Engine:** Converts admitted candidates to students, issues official matriculation numbers (`{UNI}/{YEAR}/{DEPT}/{SERIAL}`), and generates a tamper-proof digital ID token.

### 3. Academic SIS & Course Registration
* **Course Catalog:** Configurable credit units, academic levels (`100L`–`500L`, `PG`), and semester tags.
* **Credit Limit Windows:** Enforces NUC standard limits (**Minimum 15 units**, **Maximum 24 units** per semester).
* **HOD Approval Workflow:** Students submit registration; Heads of Department review, approve, or return with comments.

### 4. Learning Management System (LMS)
* **Course Materials Repository:** Lecturers upload weekly lecture slides, notes, and PDF reading materials.
* **Assignments & Grading:** Lecturers publish homework with deadlines; students upload repository or document links; lecturers grade submissions with personalized academic feedback.

### 5. Online CBT Examination Engine
* **Anti-Cheat Security:** Exam questions are served with correct answers stripped from API payloads.
* **Autosave Heartbeat (`/heartbeat`):** Periodically autosaves student answers in the background to prevent data loss during network disruptions.
* **Instant Automated Scoring:** Instant percentage calculation, pass/fail evaluation, and completion timestamping upon final submission.

### 6. NUC 5.0 CGPA Computation & Transcripts
* **Exact NUC Benchmark:**
  * 70% – 100% $\to$ **Grade A** (5.0 Points)
  * 60% – 69% $\to$ **Grade B** (4.0 Points)
  * 50% – 59% $\to$ **Grade C** (3.0 Points)
  * 45% – 49% $\to$ **Grade D** (2.0 Points)
  * 0% – 44% $\to$ **Grade F** (0.0 Points)
* **Automatic Recalculation:** Uploading CA (30 marks) + Exam (70 marks) scores automatically recalculates Semester GPA and Cumulative CGPA with class standings (*First Class*, *Second Class Upper*, *Second Class Lower*, *Third Class*, *Probation*).
* **Official PDF Transcript:** Generates printable academic transcripts with semester summaries, course breakdowns, and digital ID tokens using DomPDF.

---

## Database Structure

The database runs natively on **PostgreSQL 18** and includes **37 core tables**:
* **Tenancy & Users:** `universities`, `users`, `roles`, `permissions`, `personal_access_tokens`
* **Academic SIS:** `faculties`, `departments`, `programmes`, `staff`, `students`, `student_profiles`, `courses`, `course_registrations`, `course_registration_items`
* **Admissions:** `admissions_applications`, `jamb_records`, `olevel_results`, `post_utme_slots`
* **LMS & CBT:** `course_materials`, `assignments`, `assignment_submissions`, `cbt_exams`, `cbt_questions`, `cbt_student_sessions`
* **Grades & Results:** `course_results`, `semester_results`

---

## Installation & Quickstart

### Prerequisites
* PHP 8.2 or higher (with `pdo_pgsql` and `intl` extensions enabled)
* PostgreSQL 18 or higher
* Composer

### 1. Clone & Install Dependencies
```bash
cd netzertech-backend
composer install
```

### 2. Configure Environment (`.env`)
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=netzertech_university
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 3. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```
*Seeds two complete demo universities: **Novica University** (`NVU`) and **Apex Premier University** (`APEX`) with realistic students, staff, courses, LMS materials, CBT exams, and published results.*

### 4. Start the Local API Server
```bash
php artisan serve
```
API Base URL: `http://127.0.0.1:8000/api/v1`

---

## API Testing

### Pre-Configured Collections Available:
* ⚡ **Thunder Client Collection:** `thunder-collection_netzertech_api.json` (Includes 18 pre-configured requests across all modules).
* 📮 **Postman Collection:** `Netzertech_University_Postman_Collection.json`.

### Pre-Seeded Credentials for Quick Testing:
| Role | Institution | Identifier (Email or Matric No) | Password |
|---|---|---|---|
| **Student** | Novica University | `chidi@novicauniversity.edu.ng` or `NVU/2021/CSC/001` | `password123` |
| **Student** | Apex Premier University | `tunde.bakare@apex.edu.ng` or `APEX/2022/SWE/001` | `password123` |
| **HOD / Lecturer** | Novica University | `adebayo.olatunji@novicauniversity.edu.ng` | `password123` |
| **Dean** | Novica University | `chioma.eze@novicauniversity.edu.ng` | `password123` |
| **Applicant** | Novica University | `amina.yusuf@gmail.com` | `password123` |
| **Super Admin** | Platform Global | `admin@netzertech.com` | `admin12345` |

---

## Automated Test Suite

Run the full PHPUnit test suite:
```bash
php artisan test
```

### Test Coverage Summary:
```
   PASS  Tests\Feature\AuthAndAdmissionsTest (4 tests)
   PASS  Tests\Feature\MultiTenancyIsolationTest (4 tests)
   PASS  Tests\Feature\CourseRegistrationTest (3 tests)
   PASS  Tests\Feature\CbtEngineTest (2 tests)
   PASS  Tests\Feature\NucGpaComputationTest (4 tests)
   PASS  Tests\Unit\ExampleTest (1 test)
   PASS  Tests\Feature\ExampleTest (1 test)

  Tests:    19 passed (114 assertions)
  Duration: 4.34s
```

---

## Monetization & Split-Payment Engine

The platform implements a **Zero-CapEx Micro-Payment and Convenience Fee Model**:
* Major fee transactions (Tuition, Acceptance, Hostel) carry a small technology service charge (e.g. ₦1,500).
* Payments processed via **Paystack Subaccounts** or **Remita Split Billing** automatically split funds at the gateway level:
  * University receives 100% of their base fee into their institutional account.
  * Platform technology fee lands directly into the platform owner's corporate bank account in real-time.

---

## Contributors & Maintainers
* **Lead Engineer:** Ezekiel Hunsu ([@jscovenant](https://github.com/jscovenant))
* **Organization:** [NetzerTech Worldwide](https://github.com/NetzerTech-Worldwide)
