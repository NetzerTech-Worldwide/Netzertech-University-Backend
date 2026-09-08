# Netzertech University Management Platform — Backend API (SaaS Core)

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-blue.svg)](https://php.net)
[![PostgreSQL 18](https://img.shields.io/badge/PostgreSQL-18.x-336791.svg)](https://www.postgresql.org)
[![Redis 7](https://img.shields.io/badge/Redis-7.x-dc382d.svg)](https://redis.io)
[![Tests Passing](https://img.shields.io/badge/PHPUnit-61%20Passed%20(406%20Assertions)-success.svg)](tests)
[![Multi-Tenant Architecture](https://img.shields.io/badge/Architecture-Multi--Tenant%20SaaS-emerald.svg)](#multi-tenant-architecture)
[![Docker Ready](https://img.shields.io/badge/Docker-Production%20Ready-2496ed.svg)](#production-docker-deployment)

The enterprise backend API engine powering the **Netzertech University Platform (Novica & Multi-Tenant Tertiary Institutions)**. Built with Laravel 11, PostgreSQL 18, Redis 7, Horizon, Reverb, DomPDF, Sanctum API security, and Spatie RBAC.

---

## Table of Contents
- [Architecture & Multi-Tenancy](#multi-tenant-architecture)
- [Complete 4-Week Functional Roadmap](#complete-4-week-functional-roadmap)
  - [Week 1: Foundations, Tenancy, SIS & Admissions](#week-1-foundations-tenancy-sis--admissions)
  - [Week 2: LMS, Online CBT Engine & NUC 5.0 CGPA](#week-2-lms-online-cbt-engine--nuc-50-cgpa)
  - [Week 3: Finance, Campus Facilities & Digital ID](#week-3-finance-campus-facilities--digital-id)
  - [Week 4: Postgraduate School, AI Advisor, Career & Collaboration](#week-4-postgraduate-school-ai-advisor-career--collaboration)
- [Database Structure (52 Tables)](#database-structure)
- [Production Docker Deployment](#production-docker-deployment)
- [Installation & Local Quickstart](#installation--local-quickstart)
- [API Testing (Thunder Client & Postman Collections)](#api-testing)
- [Automated Test Suite (61 Passed, 406 Assertions)](#automated-test-suite)
- [Monetization & Payment Split Architecture](#monetization--split-payment-engine)
- [Contributors & Maintainers](#contributors--maintainers)

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

## Complete 4-Week Functional Roadmap

### Week 1: Foundations, Tenancy, SIS & Admissions
1. **Unified Authentication:** Multi-identifier login via Email, Matriculation Number (`NVU/2021/CSC/001`), or Application Code (`APP/NVU/2026/00142`).
2. **Role-Based Access Control (RBAC):** Distinct roles for `super_admin`, `dean`, `hod`, `lecturer`, `bursar`, `registrar`, `exam_officer`, `student`, `postgraduate`, and `applicant`.
3. **Institutional SIS Hierarchy:** Faculties, Departments, Degree Programmes, Staff directories, and Student academic records.
4. **Admissions Lifecycle:** Multi-step applicant registration, JAMB verification, WAEC/NECO O-Level verification, post-UTME slot booking, admission offer letter generation, and automated matriculation.

### Week 2: LMS, Online CBT Engine & NUC 5.0 CGPA
5. **Course Registration Engine:** Configurable semester credit bounds (15–24 units), prerequisite validations, and HOD approval workflows.
6. **Learning Management System (LMS):** Course lecture materials repository, assignment publication, student submissions, and rubric-based grading.
7. **Online CBT Examination Engine:** Randomized question delivery with correct answers stripped from client payloads, periodic background heartbeat sync (`/heartbeat`), and instant automated scoring.
8. **NUC 5.0 CGPA Computation Engine:** Exact National Universities Commission benchmark calculation (A=5.0, B=4.0, C=3.0, D=2.0, F=0.0) with real-time class standing classification and certified PDF transcripts via DomPDF.

### Week 3: Finance, Campus Facilities & Digital ID
9. **Finance & Invoicing Engine:** Automated fee schedule generation, split-gateway checkout (Paystack & Remita), platform convenience fee deductions, and instant receipt PDF generation.
10. **Hostel Allocation with Concurrency Protection:** Atomic Redis/DB mutex locking preventing race condition over-allocations, bed space reservation, digital code of conduct sign-off, and maintenance tickets.
11. **Campus Health Clinic (EMR):** Student electronic health records, blood group and genotype telemetry, digital health cards, doctor appointment booking, and diagnostic consultation records.
12. **Central Library System:** Physical catalog search, digital e-book / journal downloads, book reservation, and loan duration tracking.
13. **Multi-Level Approval Workflows:** Hierarchical institutional request escalation (Student $\to$ HOD $\to$ Dean $\to$ Registrar) with audit trails and step comments.
14. **Tamper-Proof Digital ID Cards:** Cryptographic token verification hash, QR code verification endpoint, and downloadable printable ID card PDFs.

### Week 4: Postgraduate School, AI Advisor, Career & Collaboration
15. **Postgraduate School (SPS) Engine:**
    * Doctoral / Masters candidate profiles and research milestone tracking.
    * Research proposal submission and defense scoring.
    * Chapter-by-chapter thesis draft review and approval workflows.
    * Supervision meeting logs with staff sign-offs.
    * **SPS Early Warning Radar Analytics:** Multidimensional risk scoring (0–100) assessing milestone delays, supervision meeting gaps, proposal status, and financial clearance to classify candidates into low, medium, and high risk.
16. **Context-Aware AI Academic Advisor:**
    * Interactive advising assistant injected with live SIS telemetry (student CGPA, current credit load, standing, registered courses).
    * Dual-mode architecture: external LLM API integration with robust heuristic domain fallback engine.
    * Multi-turn conversation persistence and session management.
17. **Career Success Portal & Automated CV Generator:**
    * Student technical skills, verified certifications, and portfolio projects.
    * Campus job board and internship listings.
    * Executive ATS-friendly PDF CV generator styled with DomPDF.
18. **Peer Collaboration & Study Groups:**
    * Course-linked study group creation with capacity enforcement.
    * Peer join flows and role assignment (Lead vs. Member).
    * Collaborative threaded group discussions.

---

## Database Structure

The production schema runs natively on **PostgreSQL 18** and includes **52 relational tables**:
* **Tenancy & Core:** `universities`, `users`, `roles`, `permissions`, `personal_access_tokens`
* **Academic SIS:** `faculties`, `departments`, `programmes`, `staff`, `students`, `student_profiles`, `courses`, `course_registrations`, `course_registration_items`
* **Admissions:** `admissions_applications`, `jamb_records`, `olevel_results`, `post_utme_slots`
* **LMS & CBT:** `course_materials`, `assignments`, `assignment_submissions`, `cbt_exams`, `cbt_questions`, `cbt_student_sessions`
* **Grades & Results:** `course_results`, `semester_results`
* **Finance:** `invoices`, `invoice_items`, `payments`
* **Facilities:** `hostels`, `hostel_rooms`, `bed_spaces`, `hostel_allocations`, `hostel_maintenance_tickets`, `clinic_registrations`, `clinic_appointments`, `library_items`, `library_borrow_records`
* **Governance & Identity:** `approval_requests`, `approval_steps`, `digital_id_cards`
* **Postgraduate (SPS):** `pg_student_profiles`, `pg_proposals`, `pg_thesis_milestones`, `pg_supervision_logs`
* **AI Advisor:** `ai_conversations`, `ai_messages`
* **Career Portal:** `student_skills`, `student_projects`, `student_certifications`, `career_jobs`
* **Collaboration:** `study_groups`, `study_group_members`, `study_group_messages`

---

## Production Docker Deployment

A complete multi-container Docker deployment blueprint is included:

```bash
# Clone the repository
git clone https://github.com/NetzerTech-Worldwide/Netzertech-University-Backend.git
cd Netzertech-University-Backend

# Start all containers in detached mode
docker compose up -d --build
```

### Containers Orchestrated:
| Container | Service | Port | Description |
|---|---|---|---|
| `netzertech_api` | PHP 8.3 FPM | 9000 | Core Laravel 11 application engine |
| `netzertech_nginx` | Nginx Alpine | 8000 | High-performance reverse proxy & static asset server |
| `netzertech_db` | PostgreSQL 16/18 | 5432 | Primary enterprise relational database |
| `netzertech_redis` | Redis 7 | 6379 | In-memory cache, session store, and queue broker |
| `netzertech_horizon` | Laravel Horizon | Internal | Redis queue worker supervisor |
| `netzertech_reverb` | Laravel Reverb | 8080 | Real-time WebSocket broadcasting server |

---

## Installation & Local Quickstart

### Prerequisites
* PHP 8.3 or higher (with `pdo_pgsql`, `pdo_sqlite`, `bcmath`, `gd`, `intl` extensions)
* PostgreSQL 18 or higher
* Composer 2.x

### 1. Install Dependencies
```bash
composer install
```

### 2. Configure Environment (`.env`)
```ini
APP_NAME="Netzertech University Platform"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=netzertech_university
DB_USERNAME=postgres
DB_PASSWORD=your_postgres_password

CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

### 3. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```
*Seeds two complete demo tertiary institutions: **Novica University** (`NVU`) and **Apex Premier University** (`APEX`), including undergraduate and postgraduate candidates, staff, exams, financial records, and SPS research portfolios.*

### 4. Start Local Development Server
```bash
php artisan serve
```
API Base URL: `http://127.0.0.1:8000/api/v1`

---

## API Testing

### Pre-Configured Collections Available:
* ⚡ **Thunder Client Collection:** `thunder-collection_netzertech_api.json` (Includes 53 pre-configured requests across all 16 domains).
* 📮 **Postman Collection (v2.1):** `Netzertech_University_Postman_Collection.json`.

### Pre-Seeded Credentials for Testing:
| Role | Institution | Identifier (Email or Matric No) | Password |
|---|---|---|---|
| **Undergraduate Student** | Novica University | `chidi@novicauniversity.edu.ng` or `NVU/2021/CSC/001` | `password123` |
| **Postgraduate Candidate** | Novica University | `fatima.pg@novicauniversity.edu.ng` or `NVU/PG/2024/0088` | `password123` |
| **Student** | Apex Premier University | `tunde.bakare@apex.edu.ng` or `APEX/2022/SWE/001` | `password123` |
| **HOD / Primary Supervisor** | Novica University | `adebayo.olatunji@novicauniversity.edu.ng` | `password123` |
| **Dean, CIT** | Novica University | `chioma.eze@novicauniversity.edu.ng` | `password123` |
| **Admissions Applicant** | Novica University | `amina.yusuf@gmail.com` | `password123` |
| **Platform Super Admin** | Platform Global | `admin@netzertech.com` | `admin12345` |

---

## Automated Test Suite

Run the full automated test suite:
```bash
php artisan test
```

### Test Suite Execution Summary:
```
   PASS  Tests\Feature\AIAdvisorTest (3 tests)
   PASS  Tests\Feature\ApprovalsWorkflowTest (5 tests)
   PASS  Tests\Feature\AuthAndAdmissionsTest (4 tests)
   PASS  Tests\Feature\CareerAndCvTest (4 tests)
   PASS  Tests\Feature\CbtEngineTest (2 tests)
   PASS  Tests\Feature\CourseRegistrationTest (3 tests)
   PASS  Tests\Feature\DigitalIdAndClinicTest (4 tests)
   PASS  Tests\Feature\FinanceAndPaymentSplitTest (7 tests)
   PASS  Tests\Feature\HostelAllocationAndMutexTest (7 tests)
   PASS  Tests\Feature\LibraryCatalogTest (7 tests)
   PASS  Tests\Feature\MultiTenancyIsolationTest (4 tests)
   PASS  Tests\Feature\NucGpaComputationTest (4 tests)
   PASS  Tests\Feature\PostgraduateSchoolTest (7 tests)
   PASS  Tests\Feature\StudyGroupsTest (4 tests)

  Tests:    61 passed (406 assertions)
  Duration: 23.05s
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
