<?php

namespace Database\Seeders;

use App\Enums\AdmissionStatus;
use App\Enums\UserType;
use App\Models\AdmissionsApplication;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\CbtExam;
use App\Models\CbtQuestion;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseRegistration;
use App\Models\CourseRegistrationItem;
use App\Models\CourseResult;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Programme;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentProfile;
use App\Models\University;
use App\Models\User;
use App\Models\ApprovalRequest;
use App\Models\ApprovalStep;
use App\Models\BedSpace;
use App\Models\ClinicAppointment;
use App\Models\ClinicRegistration;
use App\Models\DigitalIdCard;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelMaintenanceTicket;
use App\Models\HostelRoom;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\LibraryBorrowRecord;
use App\Models\LibraryItem;
use App\Models\Payment;
use App\Services\Academics\GpaCalculatorService;
use App\Services\Identity\DigitalIdCardService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with multi-tenant data.
     */
    public function run(): void
    {
        // ── 1. Create Roles ──────────────────────────────────────────────────
        $roles = [
            'super_admin',
            'dean',
            'hod',
            'lecturer',
            'bursar',
            'registrar',
            'exam_officer',
            'hostel_master',
            'student',
            'postgraduate',
            'applicant',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'api']);
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // ── 2. Create Global Platform Super Admin ───────────────────────────
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@netzertech.com'],
            [
                'university_id' => null, // Global platform admin
                'name' => 'Netzertech Platform Admin',
                'phone' => '08000000001',
                'user_type' => UserType::ADMIN->value,
                'password' => Hash::make('admin12345'),
                'is_active' => true,
            ]
        );
        $adminUser->assignRole('super_admin');

        // ── 3. Seed Tenant 1: Novica University (NVU) ────────────────────────
        $uniNovica = University::firstOrCreate(
            ['code' => 'NVU'],
            [
                'name' => 'Novica University',
                'subdomain' => 'novica',
                'custom_domain' => 'portal.novica.edu.ng',
                'primary_color' => '#2E5FA3',
                'secondary_color' => '#1A7A6E',
                'contact_email' => 'info@novicauniversity.edu.ng',
                'contact_phone' => '08012345600',
                'address' => 'University Campus Road, Lagos',
                'paystack_subaccount_code' => 'ACCT_novica123456',
                'remita_merchant_id' => 'MERCH_novica_9988',
                'platform_fee_amount' => 1500.00,
                'is_active' => true,
            ]
        );

        // Faculties & Departments for Novica
        $facultyCIT = Faculty::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'CIT'],
            ['name' => 'Faculty of Computing & Information Technology', 'description' => 'Computing and Software Sciences']
        );

        $facultyENG = Faculty::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'ENG'],
            ['name' => 'Faculty of Engineering', 'description' => 'Electrical and Mechanical Engineering']
        );

        $deptCSC = Department::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'CSC'],
            ['faculty_id' => $facultyCIT->id, 'name' => 'Department of Computer Science']
        );

        $deptIFT = Department::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'IFT'],
            ['faculty_id' => $facultyCIT->id, 'name' => 'Department of Information Technology']
        );

        $progCSC = Programme::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'B.Sc. CSC'],
            ['department_id' => $deptCSC->id, 'name' => 'B.Sc. Computer Science', 'degree' => 'B.Sc.', 'duration_years' => 4]
        );

        $progIFT = Programme::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'B.Sc. IFT'],
            ['department_id' => $deptIFT->id, 'name' => 'B.Sc. Information Technology', 'degree' => 'B.Sc.', 'duration_years' => 4]
        );

        // Staff for Novica
        $staffNovica = [
            [
                'name' => 'Prof. Adebayo Olatunji',
                'email' => 'adebayo.olatunji@novicauniversity.edu.ng',
                'staff_no' => 'STF/CIT/001',
                'title' => 'Prof.',
                'designation' => 'Head of Department',
                'rank' => 'Head of Department',
                'department_id' => $deptCSC->id,
                'faculty_id' => $facultyCIT->id,
                'initials' => 'AO',
                'color' => '#2E5FA3',
                'role' => 'hod',
            ],
            [
                'name' => 'Dr. Chioma Eze',
                'email' => 'chioma.eze@novicauniversity.edu.ng',
                'staff_no' => 'STF/CIT/002',
                'title' => 'Dr.',
                'designation' => 'Dean, Faculty of Computing & IT',
                'rank' => 'Dean',
                'department_id' => $deptCSC->id,
                'faculty_id' => $facultyCIT->id,
                'initials' => 'CE',
                'color' => '#1A7A6E',
                'role' => 'dean',
            ],
        ];

        $hodNovicaStaff = null;

        foreach ($staffNovica as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'university_id' => $uniNovica->id,
                    'name' => $item['name'],
                    'phone' => '080' . rand(10000000, 99999999),
                    'user_type' => UserType::STAFF->value,
                    'password' => Hash::make('password123'),
                    'is_active' => true,
                ]
            );
            $user->syncRoles([$item['role']]);

            $staff = Staff::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'university_id' => $uniNovica->id,
                    'staff_no' => $item['staff_no'],
                    'department_id' => $item['department_id'],
                    'faculty_id' => $item['faculty_id'],
                    'title' => $item['title'],
                    'designation' => $item['designation'],
                    'rank' => $item['rank'],
                    'initials' => $item['initials'],
                    'color' => $item['color'],
                ]
            );

            if ($item['role'] === 'dean') {
                $facultyCIT->update(['dean_staff_id' => $staff->id]);
            }
            if ($item['role'] === 'hod') {
                $deptCSC->update(['hod_staff_id' => $staff->id]);
                $hodNovicaStaff = $staff;
            }
        }

        // Student for Novica (Chidi Okonkwo)
        $studentNovicaUser = User::firstOrCreate(
            ['email' => 'chidi@novicauniversity.edu.ng'],
            [
                'university_id' => $uniNovica->id,
                'name' => 'Chidi Okonkwo',
                'phone' => '08012345678',
                'user_type' => UserType::STUDENT->value,
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $studentNovicaUser->syncRoles(['student']);

        $studentNovica = Student::updateOrCreate(
            ['user_id' => $studentNovicaUser->id],
            [
                'university_id' => $uniNovica->id,
                'matric_number' => 'NVU/2021/CSC/001',
                'jamb_reg_no' => '202140889100EF',
                'faculty_id' => $facultyCIT->id,
                'department_id' => $deptCSC->id,
                'programme_id' => $progCSC->id,
                'level' => '300L',
                'academic_session' => '2025/2026',
                'current_semester' => 'first',
                'entry_mode' => 'UTME',
                'cgpa' => 4.21,
                'standing' => 'First Class Honours',
                'digital_id_token' => hash('sha256', 'NVU/2021/CSC/001-TOKEN'),
            ]
        );

        StudentProfile::updateOrCreate(
            ['student_id' => $studentNovica->id],
            [
                'dob' => '2003-05-14',
                'gender' => 'male',
                'state_of_origin' => 'Anambra',
                'lga' => 'Idemili North',
                'nationality' => 'Nigerian',
                'address' => '14 University Road, Campus Area',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'blood_group' => 'O+',
                'genotype' => 'AA',
            ]
        );

        // Applicant for Novica (Amina Yusuf)
        $applicantNovicaUser = User::firstOrCreate(
            ['email' => 'amina.yusuf@gmail.com'],
            [
                'university_id' => $uniNovica->id,
                'name' => 'Amina Yusuf',
                'phone' => '08098765432',
                'user_type' => UserType::APPLICANT->value,
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $applicantNovicaUser->syncRoles(['applicant']);

        AdmissionsApplication::updateOrCreate(
            ['user_id' => $applicantNovicaUser->id],
            [
                'university_id' => $uniNovica->id,
                'application_no' => 'APP/NVU/2026/00142',
                'first_choice_programme_id' => $progCSC->id,
                'second_choice_programme_id' => $progIFT->id,
                'status' => AdmissionStatus::UNDER_REVIEW->value,
                'app_fee_paid' => true,
                'screening_score' => 78.50,
                'steps_completed' => [
                    'personal' => true,
                    'jamb' => true,
                    'olevel' => true,
                    'programme' => true,
                    'documents' => true,
                ],
                'submitted_at' => now()->subDays(2),
            ]
        );

        // ── Week 2: Seed Courses for Novica (CSC 300L First Semester) ────────
        $novicaCourses = [
            ['code' => 'CSC 301', 'title' => 'Data Structures & Algorithms', 'credit_units' => 3, 'level' => '300L', 'semester' => 'first'],
            ['code' => 'CSC 303', 'title' => 'Operating Systems Architecture', 'credit_units' => 3, 'level' => '300L', 'semester' => 'first'],
            ['code' => 'CSC 305', 'title' => 'Database Management Systems', 'credit_units' => 3, 'level' => '300L', 'semester' => 'first'],
            ['code' => 'CSC 307', 'title' => 'Web Application Frameworks', 'credit_units' => 3, 'level' => '300L', 'semester' => 'first'],
            ['code' => 'CSC 309', 'title' => 'Software Engineering Principles', 'credit_units' => 3, 'level' => '300L', 'semester' => 'first'],
            ['code' => 'MTH 301', 'title' => 'Numerical Analysis', 'credit_units' => 3, 'level' => '300L', 'semester' => 'first'],
        ];

        $createdNovicaCourses = [];
        foreach ($novicaCourses as $cData) {
            $createdNovicaCourses[] = Course::firstOrCreate(
                ['university_id' => $uniNovica->id, 'code' => $cData['code']],
                [
                    'department_id' => $deptCSC->id,
                    'title' => $cData['title'],
                    'credit_units' => $cData['credit_units'],
                    'level' => $cData['level'],
                    'semester' => $cData['semester'],
                    'is_active' => true,
                ]
            );
        }

        // ── Week 2: Seed Course Registration for Chidi Okonkwo (18 Credits) ──
        $regNovica = CourseRegistration::updateOrCreate(
            [
                'student_id' => $studentNovica->id,
                'academic_session' => '2025/2026',
                'semester' => 'first',
            ],
            [
                'university_id' => $uniNovica->id,
                'total_credits' => 18,
                'status' => 'approved',
                'approved_by_staff_id' => $hodNovicaStaff?->id,
                'approved_at' => now()->subWeeks(3),
            ]
        );

        foreach ($createdNovicaCourses as $c) {
            CourseRegistrationItem::firstOrCreate([
                'course_registration_id' => $regNovica->id,
                'course_id' => $c->id,
            ], ['status' => 'registered']);
        }

        // ── Week 2: Seed LMS Materials & Assignments ─────────────────────────
        $mainCourse = $createdNovicaCourses[0]; // CSC 301
        CourseMaterial::firstOrCreate(
            ['course_id' => $mainCourse->id, 'week_number' => 1],
            [
                'university_id' => $uniNovica->id,
                'uploader_staff_id' => $hodNovicaStaff->id,
                'title' => 'Week 1: Introduction to Trees and Binary Search Trees',
                'description' => 'Lecture slides and code snippets covering BST balancing algorithms.',
                'file_url' => 'https://novica.edu.ng/materials/csc301_week1.pdf',
                'file_type' => 'pdf',
                'file_size_kb' => 2048,
            ]
        );

        $assignment = Assignment::firstOrCreate(
            ['course_id' => $mainCourse->id, 'title' => 'BST Implementation in PHP/Python'],
            [
                'university_id' => $uniNovica->id,
                'creator_staff_id' => $hodNovicaStaff->id,
                'instructions' => 'Implement a self-balancing AVL tree and submit your GitHub repository link.',
                'due_date' => now()->addWeeks(2),
                'max_score' => 30.00,
                'is_published' => true,
            ]
        );

        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $studentNovica->id],
            [
                'submission_url' => 'https://github.com/chidi/avl-tree-implementation',
                'student_comment' => 'Completed AVL balancing with full unit tests.',
                'submitted_at' => now()->subDay(),
                'score' => 28.50,
                'feedback' => 'Excellent balance rotation logic.',
                'graded_by_staff_id' => $hodNovicaStaff->id,
                'graded_at' => now(),
            ]
        );

        // ── Week 2: Seed CBT Exam for CSC 301 ────────────────────────────────
        $cbtExam = CbtExam::firstOrCreate(
            ['course_id' => $mainCourse->id, 'title' => 'CSC 301 Mid-Semester Quiz'],
            [
                'university_id' => $uniNovica->id,
                'created_by_staff_id' => $hodNovicaStaff->id,
                'instructions' => 'Answer all 3 questions. Duration: 15 minutes. Attempt counts for 10% CA.',
                'duration_minutes' => 15,
                'total_marks' => 10.00,
                'pass_percentage' => 50.00,
                'start_time' => now()->subDays(1),
                'end_time' => now()->addDays(5),
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'show_result_immediately' => true,
                'is_published' => true,
            ]
        );

        $cbtQuestions = [
            [
                'text' => 'What is the worst-case time complexity of searching in an unbalanced Binary Search Tree?',
                'options' => [
                    ['id' => 'A', 'text' => 'O(1)'],
                    ['id' => 'B', 'text' => 'O(log n)'],
                    ['id' => 'C', 'text' => 'O(n)'],
                    ['id' => 'D', 'text' => 'O(n^2)'],
                ],
                'correct' => 'C',
                'points' => 3.50,
            ],
            [
                'text' => 'Which tree traversal visits the root node first?',
                'options' => [
                    ['id' => 'A', 'text' => 'In-order Traversal'],
                    ['id' => 'B', 'text' => 'Pre-order Traversal'],
                    ['id' => 'C', 'text' => 'Post-order Traversal'],
                    ['id' => 'D', 'text' => 'Level-order Traversal'],
                ],
                'correct' => 'B',
                'points' => 3.50,
            ],
            [
                'text' => 'A complete binary tree with depth d has at most 2^(d+1) - 1 nodes. Is this true or false?',
                'options' => [
                    ['id' => 'A', 'text' => 'True'],
                    ['id' => 'B', 'text' => 'False'],
                ],
                'correct' => 'A',
                'points' => 3.00,
            ],
        ];

        foreach ($cbtQuestions as $qData) {
            CbtQuestion::firstOrCreate(
                ['cbt_exam_id' => $cbtExam->id, 'question_text' => $qData['text']],
                [
                    'question_type' => 'single_choice',
                    'options' => $qData['options'],
                    'correct_answer' => $qData['correct'],
                    'points' => $qData['points'],
                ]
            );
        }

        // ── Week 2: Seed Course Results and Compute NUC 5.0 CGPA ──────────────
        $mockScores = [
            ['ca' => 28.00, 'exam' => 52.00], // 80 -> A (5.0) -> 15 CP
            ['ca' => 26.00, 'exam' => 46.00], // 72 -> A (5.0) -> 15 CP
            ['ca' => 25.00, 'exam' => 41.00], // 66 -> B (4.0) -> 12 CP
            ['ca' => 24.00, 'exam' => 40.00], // 64 -> B (4.0) -> 12 CP
            ['ca' => 28.00, 'exam' => 48.00], // 76 -> A (5.0) -> 15 CP
            ['ca' => 22.00, 'exam' => 36.00], // 58 -> C (3.0) ->  9 CP
        ];

        foreach ($createdNovicaCourses as $idx => $course) {
            $ca = $mockScores[$idx]['ca'];
            $exam = $mockScores[$idx]['exam'];
            $total = $ca + $exam;
            $grading = GpaCalculatorService::getGradeAndPoint($total);

            CourseResult::updateOrCreate(
                [
                    'student_id' => $studentNovica->id,
                    'course_id' => $course->id,
                    'academic_session' => '2025/2026',
                    'semester' => 'first',
                ],
                [
                    'university_id' => $uniNovica->id,
                    'ca_score' => $ca,
                    'exam_score' => $exam,
                    'total_score' => $total,
                    'grade' => $grading['grade'],
                    'grade_point' => $grading['grade_point'],
                    'credit_points' => round($course->credit_units * $grading['grade_point'], 2),
                    'status' => 'published',
                    'uploaded_by_staff_id' => $hodNovicaStaff->id,
                ]
            );
        }

        // Trigger NUC 5.0 GPA calculation service
        $gpaService = new GpaCalculatorService();
        $gpaService->computeSemesterGpa($studentNovica, '2025/2026', 'first', '300L');

        // ── 4. Seed Tenant 2: Apex Premier University (APEX) ─────────────────
        $uniApex = University::firstOrCreate(
            ['code' => 'APEX'],
            [
                'name' => 'Apex Premier University',
                'subdomain' => 'apex',
                'custom_domain' => 'portal.apex.edu.ng',
                'primary_color' => '#059669',
                'secondary_color' => '#047857',
                'contact_email' => 'admissions@apex.edu.ng',
                'contact_phone' => '08099881122',
                'address' => 'Apex Tech Valley, Abuja',
                'paystack_subaccount_code' => 'ACCT_apex789012',
                'remita_merchant_id' => 'MERCH_apex_3344',
                'platform_fee_amount' => 2000.00,
                'is_active' => true,
            ]
        );

        $facultyApexTech = Faculty::firstOrCreate(
            ['university_id' => $uniApex->id, 'code' => 'ENG'],
            ['name' => 'Faculty of Engineering & Emerging Technologies']
        );

        $deptApexSWE = Department::firstOrCreate(
            ['university_id' => $uniApex->id, 'code' => 'SWE'],
            ['faculty_id' => $facultyApexTech->id, 'name' => 'Department of Software Engineering']
        );

        $progApexSWE = Programme::firstOrCreate(
            ['university_id' => $uniApex->id, 'code' => 'B.Eng. SWE'],
            ['department_id' => $deptApexSWE->id, 'name' => 'B.Eng. Software Engineering', 'degree' => 'B.Eng.', 'duration_years' => 5]
        );

        // Staff for Apex
        $staffApexUser = User::firstOrCreate(
            ['email' => 'samuel.adeleke@apex.edu.ng'],
            [
                'university_id' => $uniApex->id,
                'name' => 'Prof. Samuel Adeleke',
                'phone' => '08033334444',
                'user_type' => UserType::STAFF->value,
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $staffApexUser->syncRoles(['hod']);

        $staffApex = Staff::updateOrCreate(
            ['user_id' => $staffApexUser->id],
            [
                'university_id' => $uniApex->id,
                'staff_no' => 'STF/APX/001',
                'department_id' => $deptApexSWE->id,
                'faculty_id' => $facultyApexTech->id,
                'title' => 'Prof.',
                'designation' => 'Head of Software Engineering',
                'rank' => 'Head of Department',
                'initials' => 'SA',
                'color' => '#059669',
            ]
        );

        // Student for Apex (Tunde Bakare)
        $studentApexUser = User::firstOrCreate(
            ['email' => 'tunde.bakare@apex.edu.ng'],
            [
                'university_id' => $uniApex->id,
                'name' => 'Tunde Bakare',
                'phone' => '08055667788',
                'user_type' => UserType::STUDENT->value,
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );
        $studentApexUser->syncRoles(['student']);

        $studentApex = Student::updateOrCreate(
            ['user_id' => $studentApexUser->id],
            [
                'university_id' => $uniApex->id,
                'matric_number' => 'APEX/2022/SWE/001',
                'jamb_reg_no' => '202299881122ZZ',
                'faculty_id' => $facultyApexTech->id,
                'department_id' => $deptApexSWE->id,
                'programme_id' => $progApexSWE->id,
                'level' => '200L',
                'academic_session' => '2025/2026',
                'current_semester' => 'first',
                'entry_mode' => 'UTME',
                'cgpa' => 4.50,
                'standing' => 'First Class Honours',
                'digital_id_token' => hash('sha256', 'APEX/2022/SWE/001-TOKEN'),
            ]
        );

        StudentProfile::updateOrCreate(
            ['student_id' => $studentApex->id],
            [
                'dob' => '2004-11-20',
                'gender' => 'male',
                'state_of_origin' => 'Oyo',
                'lga' => 'Ibadan North',
                'nationality' => 'Nigerian',
                'blood_group' => 'A+',
                'genotype' => 'AA',
            ]
        );

        // Courses for Apex
        Course::firstOrCreate(
            ['university_id' => $uniApex->id, 'code' => 'SWE 201'],
            [
                'department_id' => $deptApexSWE->id,
                'title' => 'Software Requirements & Architecture',
                'credit_units' => 3,
                'level' => '200L',
                'semester' => 'first',
                'is_active' => true,
            ]
        );

        // ═════════════════════════════════════════════════════════════════════
        // ── WEEK 3: SEED FINANCE, INVOICES & PAYMENTS ────────────────────────
        // ═════════════════════════════════════════════════════════════════════

        // Invoice 1: Chidi Okonkwo - Tuition Fee (Fully Paid)
        $invTuition = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-NVU-2025-001'],
            [
                'university_id' => $uniNovica->id,
                'user_id' => $studentNovicaUser->id,
                'title' => '2025/2026 First Semester Tuition Fee',
                'fee_type' => 'tuition',
                'academic_session' => '2025/2026',
                'semester' => 'first',
                'base_amount' => 120000.00,
                'platform_fee' => 1500.00,
                'total_amount' => 121500.00,
                'amount_paid' => 121500.00,
                'status' => 'paid',
                'due_date' => now()->subMonths(2)->toDateString(),
                'installment_allowed' => true,
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $invTuition->id, 'name' => 'Academic Tuition Base'],
            ['university_id' => $uniNovica->id, 'amount' => 100000.00, 'is_paid' => true]
        );
        InvoiceItem::firstOrCreate(
            ['invoice_id' => $invTuition->id, 'name' => 'Laboratory & Workshop Levy'],
            ['university_id' => $uniNovica->id, 'amount' => 15000.00, 'is_paid' => true]
        );
        InvoiceItem::firstOrCreate(
            ['invoice_id' => $invTuition->id, 'name' => 'ICT Development & Connectivity Fee'],
            ['university_id' => $uniNovica->id, 'amount' => 5000.00, 'is_paid' => true]
        );

        Payment::firstOrCreate(
            ['transaction_reference' => 'NZT-PAY-20260115-0021'],
            [
                'university_id' => $uniNovica->id,
                'invoice_id' => $invTuition->id,
                'user_id' => $studentNovicaUser->id,
                'amount' => 121500.00,
                'platform_fee' => 1500.00,
                'university_amount' => 120000.00,
                'gateway' => 'paystack',
                'gateway_reference' => 'PSTK_REF_998811',
                'status' => 'successful',
                'receipt_number' => 'RCT-20260115-0021',
                'split_code' => 'ACCT_novica123456',
                'paid_at' => now()->subMonths(2),
            ]
        );

        // Invoice 2: Chidi Okonkwo - Hostel Fee (Fully Paid)
        $invHostel = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-NVU-2025-002'],
            [
                'university_id' => $uniNovica->id,
                'user_id' => $studentNovicaUser->id,
                'title' => 'Hostel Accommodation Fee - Kings Hall (Room 204)',
                'fee_type' => 'hostel',
                'academic_session' => '2025/2026',
                'semester' => 'first',
                'base_amount' => 45000.00,
                'platform_fee' => 1500.00,
                'total_amount' => 46500.00,
                'amount_paid' => 46500.00,
                'status' => 'paid',
                'due_date' => now()->subMonths(2)->toDateString(),
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $invHostel->id, 'name' => 'Room Allocation Kings Hall - Room 204 (Bed B)'],
            ['university_id' => $uniNovica->id, 'amount' => 45000.00, 'is_paid' => true]
        );

        Payment::firstOrCreate(
            ['transaction_reference' => 'NZT-PAY-20260112-0087'],
            [
                'university_id' => $uniNovica->id,
                'invoice_id' => $invHostel->id,
                'user_id' => $studentNovicaUser->id,
                'amount' => 46500.00,
                'platform_fee' => 1500.00,
                'university_amount' => 45000.00,
                'gateway' => 'paystack',
                'gateway_reference' => 'PSTK_REF_774411',
                'status' => 'successful',
                'receipt_number' => 'HST-20260112-0087',
                'split_code' => 'ACCT_novica123456',
                'paid_at' => now()->subMonths(2),
            ]
        );

        // Invoice 3: Chidi Okonkwo - Faculty Levy (Unpaid)
        $invFaculty = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-NVU-2025-003'],
            [
                'university_id' => $uniNovica->id,
                'user_id' => $studentNovicaUser->id,
                'title' => 'Faculty & Departmental Dues',
                'fee_type' => 'faculty_levy',
                'academic_session' => '2025/2026',
                'semester' => 'first',
                'base_amount' => 7500.00,
                'platform_fee' => 1500.00,
                'total_amount' => 9000.00,
                'amount_paid' => 0.00,
                'status' => 'unpaid',
                'due_date' => now()->addDays(20)->toDateString(),
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $invFaculty->id, 'name' => 'Faculty of Computing Annual Levy'],
            ['university_id' => $uniNovica->id, 'amount' => 5000.00, 'is_paid' => false]
        );
        InvoiceItem::firstOrCreate(
            ['invoice_id' => $invFaculty->id, 'name' => 'Computer Science Students Association (NACOS) Dues'],
            ['university_id' => $uniNovica->id, 'amount' => 2500.00, 'is_paid' => false]
        );

        // Invoice 4: Amina Yusuf - Acceptance Fee (Paid)
        $invAcceptance = Invoice::firstOrCreate(
            ['invoice_number' => 'INV-NVU-2026-ACC'],
            [
                'university_id' => $uniNovica->id,
                'user_id' => $applicantNovicaUser->id,
                'title' => 'Provisional Admission Acceptance Fee',
                'fee_type' => 'acceptance',
                'academic_session' => '2026/2027',
                'base_amount' => 25000.00,
                'platform_fee' => 1500.00,
                'total_amount' => 26500.00,
                'amount_paid' => 26500.00,
                'status' => 'paid',
                'due_date' => now()->addDays(14)->toDateString(),
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $invAcceptance->id, 'name' => 'Undergraduate Acceptance Fee'],
            ['university_id' => $uniNovica->id, 'amount' => 25000.00, 'is_paid' => true]
        );

        Payment::firstOrCreate(
            ['transaction_reference' => 'NZT-PAY-20260310-0042'],
            [
                'university_id' => $uniNovica->id,
                'invoice_id' => $invAcceptance->id,
                'user_id' => $applicantNovicaUser->id,
                'amount' => 26500.00,
                'platform_fee' => 1500.00,
                'university_amount' => 25000.00,
                'gateway' => 'paystack',
                'status' => 'successful',
                'receipt_number' => 'RCT-20260310-0042',
                'split_code' => 'ACCT_novica123456',
                'paid_at' => now()->subDay(),
            ]
        );

        // ═════════════════════════════════════════════════════════════════════
        // ── WEEK 3: SEED HOSTELS, ROOMS & BED ALLOCATION ─────────────────────
        // ═════════════════════════════════════════════════════════════════════

        // Hostel 1: Kings Hall (Male)
        $hostelKings = Hostel::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'KNG'],
            [
                'name' => 'Kings Hall',
                'gender' => 'male',
                'campus_location' => 'Main Campus, West Quadrangle',
                'master_staff_id' => $hodNovicaStaff?->id,
                'capacity' => 120,
                'is_active' => true,
            ]
        );

        // Hostel 2: Queen Amina Hall (Female)
        $hostelQueen = Hostel::firstOrCreate(
            ['university_id' => $uniNovica->id, 'code' => 'QAM'],
            [
                'name' => 'Queen Amina Hall',
                'gender' => 'female',
                'campus_location' => 'Main Campus, East Quadrangle',
                'capacity' => 150,
                'is_active' => true,
            ]
        );

        // Room 204 in Kings Hall (Quad - 4 Beds)
        $room204 = HostelRoom::firstOrCreate(
            ['hostel_id' => $hostelKings->id, 'room_number' => '204'],
            [
                'university_id' => $uniNovica->id,
                'block_name' => 'Block C',
                'floor' => 2,
                'room_type' => 'quad',
                'capacity' => 4,
                'allocated_count' => 1,
                'fee_amount' => 45000.00,
            ]
        );

        $bedA = BedSpace::firstOrCreate(
            ['hostel_room_id' => $room204->id, 'bed_label' => 'Bed A'],
            ['university_id' => $uniNovica->id, 'status' => 'available']
        );
        $bedB = BedSpace::firstOrCreate(
            ['hostel_room_id' => $room204->id, 'bed_label' => 'Bed B'],
            ['university_id' => $uniNovica->id, 'status' => 'occupied']
        );
        $bedC = BedSpace::firstOrCreate(
            ['hostel_room_id' => $room204->id, 'bed_label' => 'Bed C'],
            ['university_id' => $uniNovica->id, 'status' => 'available']
        );
        $bedD = BedSpace::firstOrCreate(
            ['hostel_room_id' => $room204->id, 'bed_label' => 'Bed D'],
            ['university_id' => $uniNovica->id, 'status' => 'available']
        );

        // Room 205 in Kings Hall (All Available)
        $room205 = HostelRoom::firstOrCreate(
            ['hostel_id' => $hostelKings->id, 'room_number' => '205'],
            [
                'university_id' => $uniNovica->id,
                'block_name' => 'Block C',
                'floor' => 2,
                'room_type' => 'quad',
                'capacity' => 4,
                'allocated_count' => 0,
                'fee_amount' => 45000.00,
            ]
        );

        foreach (['Bed A', 'Bed B', 'Bed C', 'Bed D'] as $bLabel) {
            BedSpace::firstOrCreate(
                ['hostel_room_id' => $room205->id, 'bed_label' => $bLabel],
                ['university_id' => $uniNovica->id, 'status' => 'available']
            );
        }

        // Allocate Bed B to Chidi Okonkwo
        $allocChidi = HostelAllocation::firstOrCreate(
            [
                'student_id' => $studentNovica->id,
                'academic_session' => '2025/2026',
            ],
            [
                'university_id' => $uniNovica->id,
                'bed_space_id' => $bedB->id,
                'allocation_ref' => 'KHC-2026-204-CHI',
                'status' => 'confirmed',
                'rules_agreed' => true,
                'rules_agreed_at' => now()->subMonths(2),
                'check_in_date' => now()->subMonths(2)->toDateString(),
                'check_out_date' => now()->addMonths(6)->toDateString(),
            ]
        );

        // Maintenance ticket in Room 204
        HostelMaintenanceTicket::firstOrCreate(
            ['ticket_number' => 'TKT-20260215-PLM1'],
            [
                'university_id' => $uniNovica->id,
                'student_id' => $studentNovica->id,
                'hostel_room_id' => $room204->id,
                'category' => 'plumbing',
                'priority' => 'medium',
                'description' => 'Bathroom shower knob is leaking and dripping water continuously.',
                'status' => 'open',
            ]
        );

        // ═════════════════════════════════════════════════════════════════════
        // ── WEEK 3: SEED CLINIC & MEDICAL HEALTH RECORDS ─────────────────────
        // ═════════════════════════════════════════════════════════════════════

        ClinicRegistration::updateOrCreate(
            ['student_id' => $studentNovica->id],
            [
                'university_id' => $uniNovica->id,
                'hospital_number' => 'NH-NVU-2021-CSC-001',
                'blood_group' => 'O+',
                'genotype' => 'AA',
                'allergies' => 'Penicillin, Dust pollen',
                'chronic_conditions' => 'None',
                'emergency_contact_name' => 'Chief Jude Okonkwo',
                'emergency_contact_phone' => '08023456789',
                'emergency_contact_relation' => 'Father',
                'is_cleared' => true,
                'registered_at' => now()->subMonths(5),
            ]
        );

        ClinicAppointment::firstOrCreate(
            [
                'student_id' => $studentNovica->id,
                'visit_date' => now()->subWeeks(2)->toDateString(),
            ],
            [
                'university_id' => $uniNovica->id,
                'doctor_staff_id' => null,
                'symptoms' => 'Intermittent fever, joint aches, and mild headache.',
                'diagnosis' => 'Uncomplicated Plasmodium falciparum malaria.',
                'prescription' => 'Tab Artemether/Lumefantrine 80/480mg twice daily for 3 days; Tab Paracetamol 1000mg TID for 3 days.',
                'doctor_notes' => 'Patient advised to hydrate well and sleep under treated bed net.',
                'status' => 'completed',
            ]
        );

        // ═════════════════════════════════════════════════════════════════════
        // ── WEEK 3: SEED DIGITAL STUDENT ID CARD ─────────────────────────────
        // ═════════════════════════════════════════════════════════════════════

        $cardService = new DigitalIdCardService();
        $cardService->getOrCreateCard($studentNovica);

        // ═════════════════════════════════════════════════════════════════════
        // ── WEEK 3: SEED CENTRAL LIBRARY CATALOG & LOANS ─────────────────────
        // ═════════════════════════════════════════════════════════════════════

        $libraryBooks = [
            [
                'title' => 'Introduction to Algorithms (4th Edition)',
                'author' => 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest',
                'isbn' => '978-0262046305',
                'category' => 'textbook',
                'faculty' => 'Faculty of Computing & Information Technology',
                'department' => 'Department of Computer Science',
                'edition' => '4th Edition',
                'year' => 2022,
                'total_copies' => 5,
                'available_copies' => 4,
                'shelf_location' => 'Floor 2, Shelf B4',
            ],
            [
                'title' => 'Operating System Concepts (10th Edition)',
                'author' => 'Abraham Silberschatz, Peter B. Galvin, Greg Gagne',
                'isbn' => '978-1119800361',
                'category' => 'textbook',
                'faculty' => 'Faculty of Computing & Information Technology',
                'department' => 'Department of Computer Science',
                'edition' => '10th Edition',
                'year' => 2021,
                'total_copies' => 4,
                'available_copies' => 4,
                'shelf_location' => 'Floor 2, Shelf C1',
            ],
            [
                'title' => 'Database System Concepts (7th Edition)',
                'author' => 'Abraham Silberschatz, Henry F. Korth, S. Sudarshan',
                'isbn' => '978-0078022159',
                'category' => 'recommended',
                'faculty' => 'Faculty of Computing & Information Technology',
                'department' => 'Department of Computer Science',
                'edition' => '7th Edition',
                'year' => 2020,
                'total_copies' => 6,
                'available_copies' => 6,
                'shelf_location' => 'Floor 2, Shelf D2',
            ],
            [
                'title' => 'Clean Architecture: A Craftsman\'s Guide to Software Structure',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0134494166',
                'category' => 'reference',
                'faculty' => 'Faculty of Computing & Information Technology',
                'department' => 'Department of Computer Science',
                'edition' => '1st Edition',
                'year' => 2018,
                'total_copies' => 3,
                'available_copies' => 3,
                'shelf_location' => 'Floor 1, Shelf A3',
            ],
            [
                'title' => 'IEEE Transactions on Software Engineering 2025 Archive',
                'author' => 'IEEE Computer Society',
                'isbn' => 'ISSN-0098-5589',
                'category' => 'journal',
                'faculty' => 'Faculty of Computing & Information Technology',
                'department' => 'Department of Computer Science',
                'edition' => 'Vol. 51',
                'year' => 2025,
                'total_copies' => 100,
                'available_copies' => 100,
                'is_digital' => true,
                'digital_download_url' => 'https://novica.edu.ng/library/tse_2025.pdf',
            ],
        ];

        $firstBook = null;
        foreach ($libraryBooks as $bData) {
            $book = LibraryItem::firstOrCreate(
                ['university_id' => $uniNovica->id, 'title' => $bData['title']],
                array_merge($bData, ['university_id' => $uniNovica->id])
            );
            if (!$firstBook) {
                $firstBook = $book;
            }
        }

        if ($firstBook) {
            LibraryBorrowRecord::firstOrCreate(
                [
                    'student_id' => $studentNovica->id,
                    'library_item_id' => $firstBook->id,
                ],
                [
                    'university_id' => $uniNovica->id,
                    'borrow_date' => now()->subDays(5)->toDateString(),
                    'due_date' => now()->addDays(9)->toDateString(),
                    'status' => 'borrowed',
                ]
            );
        }

        // ═════════════════════════════════════════════════════════════════════
        // ── WEEK 3: SEED MULTI-LEVEL APPROVAL REQUESTS ───────────────────────
        // ═════════════════════════════════════════════════════════════════════

        $approvalReq = ApprovalRequest::firstOrCreate(
            ['request_ref' => 'REQ-2026-CFS001'],
            [
                'university_id' => $uniNovica->id,
                'student_id' => $studentNovica->id,
                'type' => 'course_form_signing',
                'title' => 'Semester Course Registration Signing (CSC 300L)',
                'reason' => 'Completed full registration of 18 credit units for CSC 300L First Semester.',
                'total_levels' => 2,
                'current_level' => 2,
                'status' => 'in_review',
            ]
        );

        ApprovalStep::firstOrCreate(
            [
                'approval_request_id' => $approvalReq->id,
                'level_number' => 1,
            ],
            [
                'university_id' => $uniNovica->id,
                'assigned_staff_id' => $hodNovicaStaff?->id,
                'required_role' => 'hod',
                'action' => 'approved',
                'comments' => 'Course prerequisites verified and credit units meet departmental requirements.',
                'acted_by_user_id' => $hodNovicaStaff?->user_id,
                'acted_at' => now()->subDays(3),
            ]
        );

        ApprovalStep::firstOrCreate(
            [
                'approval_request_id' => $approvalReq->id,
                'level_number' => 2,
            ],
            [
                'university_id' => $uniNovica->id,
                'assigned_staff_id' => $facultyCIT->dean_staff_id,
                'required_role' => 'dean',
                'action' => 'pending',
            ]
        );
    }
}

