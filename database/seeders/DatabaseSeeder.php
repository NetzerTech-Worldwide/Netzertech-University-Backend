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
use App\Services\Academics\GpaCalculatorService;
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
    }
}
