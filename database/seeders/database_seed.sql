--
-- PostgreSQL database dump
--
-- Standard PostgreSQL Seed Dump for Supabase / Cloud SQL
-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

ALTER TABLE IF EXISTS ONLY public.users DROP CONSTRAINT IF EXISTS users_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_groups DROP CONSTRAINT IF EXISTS study_groups_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_groups DROP CONSTRAINT IF EXISTS study_groups_creator_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_groups DROP CONSTRAINT IF EXISTS study_groups_course_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_group_messages DROP CONSTRAINT IF EXISTS study_group_messages_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_group_messages DROP CONSTRAINT IF EXISTS study_group_messages_study_group_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_group_messages DROP CONSTRAINT IF EXISTS study_group_messages_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_group_members DROP CONSTRAINT IF EXISTS study_group_members_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_group_members DROP CONSTRAINT IF EXISTS study_group_members_study_group_id_foreign;
ALTER TABLE IF EXISTS ONLY public.study_group_members DROP CONSTRAINT IF EXISTS study_group_members_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_programme_id_foreign;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_faculty_id_foreign;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_department_id_foreign;
ALTER TABLE IF EXISTS ONLY public.student_skills DROP CONSTRAINT IF EXISTS student_skills_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.student_skills DROP CONSTRAINT IF EXISTS student_skills_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.student_projects DROP CONSTRAINT IF EXISTS student_projects_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.student_projects DROP CONSTRAINT IF EXISTS student_projects_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.student_profiles DROP CONSTRAINT IF EXISTS student_profiles_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.student_certifications DROP CONSTRAINT IF EXISTS student_certifications_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.student_certifications DROP CONSTRAINT IF EXISTS student_certifications_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.staff DROP CONSTRAINT IF EXISTS staff_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.staff DROP CONSTRAINT IF EXISTS staff_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.staff DROP CONSTRAINT IF EXISTS staff_faculty_id_foreign;
ALTER TABLE IF EXISTS ONLY public.staff DROP CONSTRAINT IF EXISTS staff_department_id_foreign;
ALTER TABLE IF EXISTS ONLY public.semester_results DROP CONSTRAINT IF EXISTS semester_results_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.semester_results DROP CONSTRAINT IF EXISTS semester_results_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.role_has_permissions DROP CONSTRAINT IF EXISTS role_has_permissions_role_id_foreign;
ALTER TABLE IF EXISTS ONLY public.role_has_permissions DROP CONSTRAINT IF EXISTS role_has_permissions_permission_id_foreign;
ALTER TABLE IF EXISTS ONLY public.programmes DROP CONSTRAINT IF EXISTS programmes_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.programmes DROP CONSTRAINT IF EXISTS programmes_department_id_foreign;
ALTER TABLE IF EXISTS ONLY public.post_utme_slots DROP CONSTRAINT IF EXISTS post_utme_slots_application_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_thesis_milestones DROP CONSTRAINT IF EXISTS pg_thesis_milestones_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_thesis_milestones DROP CONSTRAINT IF EXISTS pg_thesis_milestones_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_supervision_logs DROP CONSTRAINT IF EXISTS pg_supervision_logs_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_supervision_logs DROP CONSTRAINT IF EXISTS pg_supervision_logs_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_supervision_logs DROP CONSTRAINT IF EXISTS pg_supervision_logs_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_student_profiles DROP CONSTRAINT IF EXISTS pg_student_profiles_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_student_profiles DROP CONSTRAINT IF EXISTS pg_student_profiles_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_student_profiles DROP CONSTRAINT IF EXISTS pg_student_profiles_primary_supervisor_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_student_profiles DROP CONSTRAINT IF EXISTS pg_student_profiles_co_supervisor_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_proposals DROP CONSTRAINT IF EXISTS pg_proposals_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.pg_proposals DROP CONSTRAINT IF EXISTS pg_proposals_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.payments DROP CONSTRAINT IF EXISTS payments_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.payments DROP CONSTRAINT IF EXISTS payments_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.payments DROP CONSTRAINT IF EXISTS payments_invoice_id_foreign;
ALTER TABLE IF EXISTS ONLY public.olevel_results DROP CONSTRAINT IF EXISTS olevel_results_application_id_foreign;
ALTER TABLE IF EXISTS ONLY public.model_has_roles DROP CONSTRAINT IF EXISTS model_has_roles_role_id_foreign;
ALTER TABLE IF EXISTS ONLY public.model_has_permissions DROP CONSTRAINT IF EXISTS model_has_permissions_permission_id_foreign;
ALTER TABLE IF EXISTS ONLY public.library_items DROP CONSTRAINT IF EXISTS library_items_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.library_borrow_records DROP CONSTRAINT IF EXISTS library_borrow_records_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.library_borrow_records DROP CONSTRAINT IF EXISTS library_borrow_records_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.library_borrow_records DROP CONSTRAINT IF EXISTS library_borrow_records_library_item_id_foreign;
ALTER TABLE IF EXISTS ONLY public.jamb_records DROP CONSTRAINT IF EXISTS jamb_records_application_id_foreign;
ALTER TABLE IF EXISTS ONLY public.invoices DROP CONSTRAINT IF EXISTS invoices_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.invoices DROP CONSTRAINT IF EXISTS invoices_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.invoice_items DROP CONSTRAINT IF EXISTS invoice_items_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.invoice_items DROP CONSTRAINT IF EXISTS invoice_items_invoice_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostels DROP CONSTRAINT IF EXISTS hostels_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostels DROP CONSTRAINT IF EXISTS hostels_master_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_rooms DROP CONSTRAINT IF EXISTS hostel_rooms_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_rooms DROP CONSTRAINT IF EXISTS hostel_rooms_hostel_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_maintenance_tickets DROP CONSTRAINT IF EXISTS hostel_maintenance_tickets_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_maintenance_tickets DROP CONSTRAINT IF EXISTS hostel_maintenance_tickets_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_maintenance_tickets DROP CONSTRAINT IF EXISTS hostel_maintenance_tickets_resolved_by_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_maintenance_tickets DROP CONSTRAINT IF EXISTS hostel_maintenance_tickets_hostel_room_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_allocations DROP CONSTRAINT IF EXISTS hostel_allocations_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_allocations DROP CONSTRAINT IF EXISTS hostel_allocations_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.hostel_allocations DROP CONSTRAINT IF EXISTS hostel_allocations_bed_space_id_foreign;
ALTER TABLE IF EXISTS ONLY public.faculties DROP CONSTRAINT IF EXISTS faculties_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.digital_id_cards DROP CONSTRAINT IF EXISTS digital_id_cards_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.digital_id_cards DROP CONSTRAINT IF EXISTS digital_id_cards_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.departments DROP CONSTRAINT IF EXISTS departments_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.departments DROP CONSTRAINT IF EXISTS departments_faculty_id_foreign;
ALTER TABLE IF EXISTS ONLY public.courses DROP CONSTRAINT IF EXISTS courses_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.courses DROP CONSTRAINT IF EXISTS courses_prerequisite_course_id_foreign;
ALTER TABLE IF EXISTS ONLY public.courses DROP CONSTRAINT IF EXISTS courses_department_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_results DROP CONSTRAINT IF EXISTS course_results_uploaded_by_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_results DROP CONSTRAINT IF EXISTS course_results_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_results DROP CONSTRAINT IF EXISTS course_results_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_results DROP CONSTRAINT IF EXISTS course_results_course_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_registrations DROP CONSTRAINT IF EXISTS course_registrations_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_registrations DROP CONSTRAINT IF EXISTS course_registrations_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_registrations DROP CONSTRAINT IF EXISTS course_registrations_approved_by_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_registration_items DROP CONSTRAINT IF EXISTS course_registration_items_course_registration_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_registration_items DROP CONSTRAINT IF EXISTS course_registration_items_course_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_materials DROP CONSTRAINT IF EXISTS course_materials_uploader_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_materials DROP CONSTRAINT IF EXISTS course_materials_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.course_materials DROP CONSTRAINT IF EXISTS course_materials_course_id_foreign;
ALTER TABLE IF EXISTS ONLY public.clinic_registrations DROP CONSTRAINT IF EXISTS clinic_registrations_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.clinic_registrations DROP CONSTRAINT IF EXISTS clinic_registrations_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.clinic_appointments DROP CONSTRAINT IF EXISTS clinic_appointments_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.clinic_appointments DROP CONSTRAINT IF EXISTS clinic_appointments_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.clinic_appointments DROP CONSTRAINT IF EXISTS clinic_appointments_doctor_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cbt_student_sessions DROP CONSTRAINT IF EXISTS cbt_student_sessions_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cbt_student_sessions DROP CONSTRAINT IF EXISTS cbt_student_sessions_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cbt_student_sessions DROP CONSTRAINT IF EXISTS cbt_student_sessions_cbt_exam_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cbt_questions DROP CONSTRAINT IF EXISTS cbt_questions_cbt_exam_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cbt_exams DROP CONSTRAINT IF EXISTS cbt_exams_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cbt_exams DROP CONSTRAINT IF EXISTS cbt_exams_created_by_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.cbt_exams DROP CONSTRAINT IF EXISTS cbt_exams_course_id_foreign;
ALTER TABLE IF EXISTS ONLY public.career_jobs DROP CONSTRAINT IF EXISTS career_jobs_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.bed_spaces DROP CONSTRAINT IF EXISTS bed_spaces_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.bed_spaces DROP CONSTRAINT IF EXISTS bed_spaces_hostel_room_id_foreign;
ALTER TABLE IF EXISTS ONLY public.assignments DROP CONSTRAINT IF EXISTS assignments_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.assignments DROP CONSTRAINT IF EXISTS assignments_creator_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.assignments DROP CONSTRAINT IF EXISTS assignments_course_id_foreign;
ALTER TABLE IF EXISTS ONLY public.assignment_submissions DROP CONSTRAINT IF EXISTS assignment_submissions_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.assignment_submissions DROP CONSTRAINT IF EXISTS assignment_submissions_graded_by_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.assignment_submissions DROP CONSTRAINT IF EXISTS assignment_submissions_assignment_id_foreign;
ALTER TABLE IF EXISTS ONLY public.approval_steps DROP CONSTRAINT IF EXISTS approval_steps_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.approval_steps DROP CONSTRAINT IF EXISTS approval_steps_assigned_staff_id_foreign;
ALTER TABLE IF EXISTS ONLY public.approval_steps DROP CONSTRAINT IF EXISTS approval_steps_approval_request_id_foreign;
ALTER TABLE IF EXISTS ONLY public.approval_steps DROP CONSTRAINT IF EXISTS approval_steps_acted_by_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.approval_requests DROP CONSTRAINT IF EXISTS approval_requests_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.approval_requests DROP CONSTRAINT IF EXISTS approval_requests_student_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ai_messages DROP CONSTRAINT IF EXISTS ai_messages_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ai_messages DROP CONSTRAINT IF EXISTS ai_messages_conversation_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ai_conversations DROP CONSTRAINT IF EXISTS ai_conversations_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.ai_conversations DROP CONSTRAINT IF EXISTS ai_conversations_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.admissions_applications DROP CONSTRAINT IF EXISTS admissions_applications_user_id_foreign;
ALTER TABLE IF EXISTS ONLY public.admissions_applications DROP CONSTRAINT IF EXISTS admissions_applications_university_id_foreign;
ALTER TABLE IF EXISTS ONLY public.admissions_applications DROP CONSTRAINT IF EXISTS admissions_applications_second_choice_programme_id_foreign;
ALTER TABLE IF EXISTS ONLY public.admissions_applications DROP CONSTRAINT IF EXISTS admissions_applications_first_choice_programme_id_foreign;
DROP INDEX IF EXISTS public.study_groups_university_id_course_id_index;
DROP INDEX IF EXISTS public.study_group_messages_university_id_study_group_id_index;
DROP INDEX IF EXISTS public.student_skills_university_id_student_id_index;
DROP INDEX IF EXISTS public.student_projects_university_id_student_id_index;
DROP INDEX IF EXISTS public.student_certifications_university_id_student_id_index;
DROP INDEX IF EXISTS public.sessions_user_id_index;
DROP INDEX IF EXISTS public.sessions_last_activity_index;
DROP INDEX IF EXISTS public.pg_thesis_milestones_university_id_student_id_status_index;
DROP INDEX IF EXISTS public.pg_supervision_logs_university_id_student_id_meeting_date_index;
DROP INDEX IF EXISTS public.pg_student_profiles_university_id_risk_level_index;
DROP INDEX IF EXISTS public.pg_student_profiles_university_id_current_stage_index;
DROP INDEX IF EXISTS public.pg_proposals_university_id_student_id_status_index;
DROP INDEX IF EXISTS public.personal_access_tokens_tokenable_type_tokenable_id_index;
DROP INDEX IF EXISTS public.personal_access_tokens_expires_at_index;
DROP INDEX IF EXISTS public.payments_university_id_transaction_reference_index;
DROP INDEX IF EXISTS public.payments_university_id_status_index;
DROP INDEX IF EXISTS public.model_has_roles_model_id_model_type_index;
DROP INDEX IF EXISTS public.model_has_permissions_model_id_model_type_index;
DROP INDEX IF EXISTS public.library_items_university_id_title_index;
DROP INDEX IF EXISTS public.library_items_university_id_category_index;
DROP INDEX IF EXISTS public.library_borrow_records_university_id_student_id_status_index;
DROP INDEX IF EXISTS public.library_borrow_records_university_id_library_item_id_index;
DROP INDEX IF EXISTS public.jobs_queue_index;
DROP INDEX IF EXISTS public.invoices_university_id_user_id_status_index;
DROP INDEX IF EXISTS public.invoices_university_id_fee_type_academic_session_index;
DROP INDEX IF EXISTS public.invoice_items_university_id_invoice_id_index;
DROP INDEX IF EXISTS public.hostels_university_id_gender_index;
DROP INDEX IF EXISTS public.hostel_rooms_university_id_hostel_id_index;
DROP INDEX IF EXISTS public.hostel_maintenance_tickets_university_id_student_id_index;
DROP INDEX IF EXISTS public.hostel_maintenance_tickets_university_id_hostel_room_id_status_;
DROP INDEX IF EXISTS public.hostel_allocations_university_id_student_id_academic_session_in;
DROP INDEX IF EXISTS public.hostel_allocations_university_id_bed_space_id_index;
DROP INDEX IF EXISTS public.failed_jobs_connection_queue_failed_at_index;
DROP INDEX IF EXISTS public.digital_id_cards_university_id_barcode_hash_index;
DROP INDEX IF EXISTS public.clinic_registrations_university_id_hospital_number_index;
DROP INDEX IF EXISTS public.clinic_appointments_university_id_student_id_visit_date_index;
DROP INDEX IF EXISTS public.clinic_appointments_university_id_status_index;
DROP INDEX IF EXISTS public.career_jobs_university_id_is_active_job_type_index;
DROP INDEX IF EXISTS public.cache_locks_expiration_index;
DROP INDEX IF EXISTS public.cache_expiration_index;
DROP INDEX IF EXISTS public.bed_spaces_university_id_status_index;
DROP INDEX IF EXISTS public.approval_steps_university_id_required_role_action_index;
DROP INDEX IF EXISTS public.approval_steps_university_id_approval_request_id_level_number_i;
DROP INDEX IF EXISTS public.approval_requests_university_id_type_index;
DROP INDEX IF EXISTS public.approval_requests_university_id_student_id_status_index;
DROP INDEX IF EXISTS public.ai_messages_university_id_conversation_id_index;
DROP INDEX IF EXISTS public.ai_conversations_university_id_user_id_session_id_index;
ALTER TABLE IF EXISTS ONLY public.users DROP CONSTRAINT IF EXISTS users_pkey;
ALTER TABLE IF EXISTS ONLY public.users DROP CONSTRAINT IF EXISTS users_phone_unique;
ALTER TABLE IF EXISTS ONLY public.users DROP CONSTRAINT IF EXISTS users_email_unique;
ALTER TABLE IF EXISTS ONLY public.universities DROP CONSTRAINT IF EXISTS universities_subdomain_unique;
ALTER TABLE IF EXISTS ONLY public.universities DROP CONSTRAINT IF EXISTS universities_pkey;
ALTER TABLE IF EXISTS ONLY public.universities DROP CONSTRAINT IF EXISTS universities_custom_domain_unique;
ALTER TABLE IF EXISTS ONLY public.universities DROP CONSTRAINT IF EXISTS universities_code_unique;
ALTER TABLE IF EXISTS ONLY public.study_groups DROP CONSTRAINT IF EXISTS study_groups_pkey;
ALTER TABLE IF EXISTS ONLY public.study_group_messages DROP CONSTRAINT IF EXISTS study_group_messages_pkey;
ALTER TABLE IF EXISTS ONLY public.study_group_members DROP CONSTRAINT IF EXISTS study_group_members_study_group_id_student_id_unique;
ALTER TABLE IF EXISTS ONLY public.study_group_members DROP CONSTRAINT IF EXISTS study_group_members_pkey;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_university_id_matric_number_unique;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_pkey;
ALTER TABLE IF EXISTS ONLY public.students DROP CONSTRAINT IF EXISTS students_digital_id_token_unique;
ALTER TABLE IF EXISTS ONLY public.student_skills DROP CONSTRAINT IF EXISTS student_skills_pkey;
ALTER TABLE IF EXISTS ONLY public.student_projects DROP CONSTRAINT IF EXISTS student_projects_pkey;
ALTER TABLE IF EXISTS ONLY public.student_profiles DROP CONSTRAINT IF EXISTS student_profiles_pkey;
ALTER TABLE IF EXISTS ONLY public.student_certifications DROP CONSTRAINT IF EXISTS student_certifications_pkey;
ALTER TABLE IF EXISTS ONLY public.staff DROP CONSTRAINT IF EXISTS staff_university_id_staff_no_unique;
ALTER TABLE IF EXISTS ONLY public.staff DROP CONSTRAINT IF EXISTS staff_pkey;
ALTER TABLE IF EXISTS ONLY public.sessions DROP CONSTRAINT IF EXISTS sessions_pkey;
ALTER TABLE IF EXISTS ONLY public.semester_results DROP CONSTRAINT IF EXISTS semester_results_student_id_academic_session_semester_unique;
ALTER TABLE IF EXISTS ONLY public.semester_results DROP CONSTRAINT IF EXISTS semester_results_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_pkey;
ALTER TABLE IF EXISTS ONLY public.roles DROP CONSTRAINT IF EXISTS roles_name_guard_name_unique;
ALTER TABLE IF EXISTS ONLY public.role_has_permissions DROP CONSTRAINT IF EXISTS role_has_permissions_pkey;
ALTER TABLE IF EXISTS ONLY public.programmes DROP CONSTRAINT IF EXISTS programmes_university_id_code_unique;
ALTER TABLE IF EXISTS ONLY public.programmes DROP CONSTRAINT IF EXISTS programmes_pkey;
ALTER TABLE IF EXISTS ONLY public.post_utme_slots DROP CONSTRAINT IF EXISTS post_utme_slots_pkey;
ALTER TABLE IF EXISTS ONLY public.pg_thesis_milestones DROP CONSTRAINT IF EXISTS pg_thesis_milestones_student_id_chapter_number_unique;
ALTER TABLE IF EXISTS ONLY public.pg_thesis_milestones DROP CONSTRAINT IF EXISTS pg_thesis_milestones_pkey;
ALTER TABLE IF EXISTS ONLY public.pg_supervision_logs DROP CONSTRAINT IF EXISTS pg_supervision_logs_pkey;
ALTER TABLE IF EXISTS ONLY public.pg_student_profiles DROP CONSTRAINT IF EXISTS pg_student_profiles_university_id_student_id_unique;
ALTER TABLE IF EXISTS ONLY public.pg_student_profiles DROP CONSTRAINT IF EXISTS pg_student_profiles_pkey;
ALTER TABLE IF EXISTS ONLY public.pg_proposals DROP CONSTRAINT IF EXISTS pg_proposals_pkey;
ALTER TABLE IF EXISTS ONLY public.personal_access_tokens DROP CONSTRAINT IF EXISTS personal_access_tokens_token_unique;
ALTER TABLE IF EXISTS ONLY public.personal_access_tokens DROP CONSTRAINT IF EXISTS personal_access_tokens_pkey;
ALTER TABLE IF EXISTS ONLY public.permissions DROP CONSTRAINT IF EXISTS permissions_pkey;
ALTER TABLE IF EXISTS ONLY public.permissions DROP CONSTRAINT IF EXISTS permissions_name_guard_name_unique;
ALTER TABLE IF EXISTS ONLY public.payments DROP CONSTRAINT IF EXISTS payments_transaction_reference_unique;
ALTER TABLE IF EXISTS ONLY public.payments DROP CONSTRAINT IF EXISTS payments_receipt_number_unique;
ALTER TABLE IF EXISTS ONLY public.payments DROP CONSTRAINT IF EXISTS payments_pkey;
ALTER TABLE IF EXISTS ONLY public.password_reset_tokens DROP CONSTRAINT IF EXISTS password_reset_tokens_pkey;
ALTER TABLE IF EXISTS ONLY public.olevel_results DROP CONSTRAINT IF EXISTS olevel_results_pkey;
ALTER TABLE IF EXISTS ONLY public.model_has_roles DROP CONSTRAINT IF EXISTS model_has_roles_pkey;
ALTER TABLE IF EXISTS ONLY public.model_has_permissions DROP CONSTRAINT IF EXISTS model_has_permissions_pkey;
ALTER TABLE IF EXISTS ONLY public.migrations DROP CONSTRAINT IF EXISTS migrations_pkey;
ALTER TABLE IF EXISTS ONLY public.library_items DROP CONSTRAINT IF EXISTS library_items_pkey;
ALTER TABLE IF EXISTS ONLY public.library_borrow_records DROP CONSTRAINT IF EXISTS library_borrow_records_pkey;
ALTER TABLE IF EXISTS ONLY public.jobs DROP CONSTRAINT IF EXISTS jobs_pkey;
ALTER TABLE IF EXISTS ONLY public.job_batches DROP CONSTRAINT IF EXISTS job_batches_pkey;
ALTER TABLE IF EXISTS ONLY public.jamb_records DROP CONSTRAINT IF EXISTS jamb_records_pkey;
ALTER TABLE IF EXISTS ONLY public.invoices DROP CONSTRAINT IF EXISTS invoices_pkey;
ALTER TABLE IF EXISTS ONLY public.invoices DROP CONSTRAINT IF EXISTS invoices_invoice_number_unique;
ALTER TABLE IF EXISTS ONLY public.invoice_items DROP CONSTRAINT IF EXISTS invoice_items_pkey;
ALTER TABLE IF EXISTS ONLY public.hostels DROP CONSTRAINT IF EXISTS hostels_university_id_code_unique;
ALTER TABLE IF EXISTS ONLY public.hostels DROP CONSTRAINT IF EXISTS hostels_pkey;
ALTER TABLE IF EXISTS ONLY public.hostel_rooms DROP CONSTRAINT IF EXISTS hostel_rooms_pkey;
ALTER TABLE IF EXISTS ONLY public.hostel_rooms DROP CONSTRAINT IF EXISTS hostel_rooms_hostel_id_room_number_unique;
ALTER TABLE IF EXISTS ONLY public.hostel_maintenance_tickets DROP CONSTRAINT IF EXISTS hostel_maintenance_tickets_ticket_number_unique;
ALTER TABLE IF EXISTS ONLY public.hostel_maintenance_tickets DROP CONSTRAINT IF EXISTS hostel_maintenance_tickets_pkey;
ALTER TABLE IF EXISTS ONLY public.hostel_allocations DROP CONSTRAINT IF EXISTS hostel_allocations_pkey;
ALTER TABLE IF EXISTS ONLY public.hostel_allocations DROP CONSTRAINT IF EXISTS hostel_allocations_allocation_ref_unique;
ALTER TABLE IF EXISTS ONLY public.failed_jobs DROP CONSTRAINT IF EXISTS failed_jobs_uuid_unique;
ALTER TABLE IF EXISTS ONLY public.failed_jobs DROP CONSTRAINT IF EXISTS failed_jobs_pkey;
ALTER TABLE IF EXISTS ONLY public.faculties DROP CONSTRAINT IF EXISTS faculties_university_id_code_unique;
ALTER TABLE IF EXISTS ONLY public.faculties DROP CONSTRAINT IF EXISTS faculties_pkey;
ALTER TABLE IF EXISTS ONLY public.digital_id_cards DROP CONSTRAINT IF EXISTS digital_id_cards_university_id_student_id_unique;
ALTER TABLE IF EXISTS ONLY public.digital_id_cards DROP CONSTRAINT IF EXISTS digital_id_cards_pkey;
ALTER TABLE IF EXISTS ONLY public.digital_id_cards DROP CONSTRAINT IF EXISTS digital_id_cards_card_number_unique;
ALTER TABLE IF EXISTS ONLY public.digital_id_cards DROP CONSTRAINT IF EXISTS digital_id_cards_barcode_hash_unique;
ALTER TABLE IF EXISTS ONLY public.departments DROP CONSTRAINT IF EXISTS departments_university_id_code_unique;
ALTER TABLE IF EXISTS ONLY public.departments DROP CONSTRAINT IF EXISTS departments_pkey;
ALTER TABLE IF EXISTS ONLY public.courses DROP CONSTRAINT IF EXISTS courses_university_id_code_unique;
ALTER TABLE IF EXISTS ONLY public.courses DROP CONSTRAINT IF EXISTS courses_pkey;
ALTER TABLE IF EXISTS ONLY public.course_results DROP CONSTRAINT IF EXISTS course_results_student_id_course_id_academic_session_semester_u;
ALTER TABLE IF EXISTS ONLY public.course_results DROP CONSTRAINT IF EXISTS course_results_pkey;
ALTER TABLE IF EXISTS ONLY public.course_registrations DROP CONSTRAINT IF EXISTS course_registrations_student_id_academic_session_semester_uniqu;
ALTER TABLE IF EXISTS ONLY public.course_registrations DROP CONSTRAINT IF EXISTS course_registrations_pkey;
ALTER TABLE IF EXISTS ONLY public.course_registration_items DROP CONSTRAINT IF EXISTS course_registration_items_pkey;
ALTER TABLE IF EXISTS ONLY public.course_registration_items DROP CONSTRAINT IF EXISTS course_registration_items_course_registration_id_course_id_uniq;
ALTER TABLE IF EXISTS ONLY public.course_materials DROP CONSTRAINT IF EXISTS course_materials_pkey;
ALTER TABLE IF EXISTS ONLY public.clinic_registrations DROP CONSTRAINT IF EXISTS clinic_registrations_university_id_student_id_unique;
ALTER TABLE IF EXISTS ONLY public.clinic_registrations DROP CONSTRAINT IF EXISTS clinic_registrations_pkey;
ALTER TABLE IF EXISTS ONLY public.clinic_registrations DROP CONSTRAINT IF EXISTS clinic_registrations_hospital_number_unique;
ALTER TABLE IF EXISTS ONLY public.clinic_appointments DROP CONSTRAINT IF EXISTS clinic_appointments_pkey;
ALTER TABLE IF EXISTS ONLY public.cbt_student_sessions DROP CONSTRAINT IF EXISTS cbt_student_sessions_pkey;
ALTER TABLE IF EXISTS ONLY public.cbt_student_sessions DROP CONSTRAINT IF EXISTS cbt_student_sessions_cbt_exam_id_student_id_unique;
ALTER TABLE IF EXISTS ONLY public.cbt_questions DROP CONSTRAINT IF EXISTS cbt_questions_pkey;
ALTER TABLE IF EXISTS ONLY public.cbt_exams DROP CONSTRAINT IF EXISTS cbt_exams_pkey;
ALTER TABLE IF EXISTS ONLY public.career_jobs DROP CONSTRAINT IF EXISTS career_jobs_pkey;
ALTER TABLE IF EXISTS ONLY public.cache DROP CONSTRAINT IF EXISTS cache_pkey;
ALTER TABLE IF EXISTS ONLY public.cache_locks DROP CONSTRAINT IF EXISTS cache_locks_pkey;
ALTER TABLE IF EXISTS ONLY public.bed_spaces DROP CONSTRAINT IF EXISTS bed_spaces_pkey;
ALTER TABLE IF EXISTS ONLY public.bed_spaces DROP CONSTRAINT IF EXISTS bed_spaces_hostel_room_id_bed_label_unique;
ALTER TABLE IF EXISTS ONLY public.assignments DROP CONSTRAINT IF EXISTS assignments_pkey;
ALTER TABLE IF EXISTS ONLY public.assignment_submissions DROP CONSTRAINT IF EXISTS assignment_submissions_pkey;
ALTER TABLE IF EXISTS ONLY public.assignment_submissions DROP CONSTRAINT IF EXISTS assignment_submissions_assignment_id_student_id_unique;
ALTER TABLE IF EXISTS ONLY public.approval_steps DROP CONSTRAINT IF EXISTS approval_steps_pkey;
ALTER TABLE IF EXISTS ONLY public.approval_requests DROP CONSTRAINT IF EXISTS approval_requests_request_ref_unique;
ALTER TABLE IF EXISTS ONLY public.approval_requests DROP CONSTRAINT IF EXISTS approval_requests_pkey;
ALTER TABLE IF EXISTS ONLY public.ai_messages DROP CONSTRAINT IF EXISTS ai_messages_pkey;
ALTER TABLE IF EXISTS ONLY public.ai_conversations DROP CONSTRAINT IF EXISTS ai_conversations_pkey;
ALTER TABLE IF EXISTS ONLY public.admissions_applications DROP CONSTRAINT IF EXISTS admissions_applications_university_id_application_no_unique;
ALTER TABLE IF EXISTS ONLY public.admissions_applications DROP CONSTRAINT IF EXISTS admissions_applications_pkey;
ALTER TABLE IF EXISTS public.users ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.universities ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.study_groups ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.study_group_messages ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.study_group_members ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.students ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.student_skills ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.student_projects ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.student_profiles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.student_certifications ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.staff ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.semester_results ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.roles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.programmes ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.post_utme_slots ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.pg_thesis_milestones ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.pg_supervision_logs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.pg_student_profiles ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.pg_proposals ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.personal_access_tokens ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.permissions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.payments ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.olevel_results ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.migrations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.library_items ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.library_borrow_records ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.jobs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.jamb_records ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.invoices ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.invoice_items ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.hostels ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.hostel_rooms ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.hostel_maintenance_tickets ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.hostel_allocations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.failed_jobs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.faculties ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.digital_id_cards ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.departments ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.courses ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.course_results ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.course_registrations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.course_registration_items ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.course_materials ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.clinic_registrations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.clinic_appointments ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cbt_student_sessions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cbt_questions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.cbt_exams ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.career_jobs ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.bed_spaces ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.assignments ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.assignment_submissions ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.approval_steps ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.approval_requests ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ai_messages ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.ai_conversations ALTER COLUMN id DROP DEFAULT;
ALTER TABLE IF EXISTS public.admissions_applications ALTER COLUMN id DROP DEFAULT;
DROP SEQUENCE IF EXISTS public.users_id_seq;
DROP TABLE IF EXISTS public.users;
DROP SEQUENCE IF EXISTS public.universities_id_seq;
DROP TABLE IF EXISTS public.universities;
DROP SEQUENCE IF EXISTS public.study_groups_id_seq;
DROP TABLE IF EXISTS public.study_groups;
DROP SEQUENCE IF EXISTS public.study_group_messages_id_seq;
DROP TABLE IF EXISTS public.study_group_messages;
DROP SEQUENCE IF EXISTS public.study_group_members_id_seq;
DROP TABLE IF EXISTS public.study_group_members;
DROP SEQUENCE IF EXISTS public.students_id_seq;
DROP TABLE IF EXISTS public.students;
DROP SEQUENCE IF EXISTS public.student_skills_id_seq;
DROP TABLE IF EXISTS public.student_skills;
DROP SEQUENCE IF EXISTS public.student_projects_id_seq;
DROP TABLE IF EXISTS public.student_projects;
DROP SEQUENCE IF EXISTS public.student_profiles_id_seq;
DROP TABLE IF EXISTS public.student_profiles;
DROP SEQUENCE IF EXISTS public.student_certifications_id_seq;
DROP TABLE IF EXISTS public.student_certifications;
DROP SEQUENCE IF EXISTS public.staff_id_seq;
DROP TABLE IF EXISTS public.staff;
DROP TABLE IF EXISTS public.sessions;
DROP SEQUENCE IF EXISTS public.semester_results_id_seq;
DROP TABLE IF EXISTS public.semester_results;
DROP SEQUENCE IF EXISTS public.roles_id_seq;
DROP TABLE IF EXISTS public.roles;
DROP TABLE IF EXISTS public.role_has_permissions;
DROP SEQUENCE IF EXISTS public.programmes_id_seq;
DROP TABLE IF EXISTS public.programmes;
DROP SEQUENCE IF EXISTS public.post_utme_slots_id_seq;
DROP TABLE IF EXISTS public.post_utme_slots;
DROP SEQUENCE IF EXISTS public.pg_thesis_milestones_id_seq;
DROP TABLE IF EXISTS public.pg_thesis_milestones;
DROP SEQUENCE IF EXISTS public.pg_supervision_logs_id_seq;
DROP TABLE IF EXISTS public.pg_supervision_logs;
DROP SEQUENCE IF EXISTS public.pg_student_profiles_id_seq;
DROP TABLE IF EXISTS public.pg_student_profiles;
DROP SEQUENCE IF EXISTS public.pg_proposals_id_seq;
DROP TABLE IF EXISTS public.pg_proposals;
DROP SEQUENCE IF EXISTS public.personal_access_tokens_id_seq;
DROP TABLE IF EXISTS public.personal_access_tokens;
DROP SEQUENCE IF EXISTS public.permissions_id_seq;
DROP TABLE IF EXISTS public.permissions;
DROP SEQUENCE IF EXISTS public.payments_id_seq;
DROP TABLE IF EXISTS public.payments;
DROP TABLE IF EXISTS public.password_reset_tokens;
DROP SEQUENCE IF EXISTS public.olevel_results_id_seq;
DROP TABLE IF EXISTS public.olevel_results;
DROP TABLE IF EXISTS public.model_has_roles;
DROP TABLE IF EXISTS public.model_has_permissions;
DROP SEQUENCE IF EXISTS public.migrations_id_seq;
DROP TABLE IF EXISTS public.migrations;
DROP SEQUENCE IF EXISTS public.library_items_id_seq;
DROP TABLE IF EXISTS public.library_items;
DROP SEQUENCE IF EXISTS public.library_borrow_records_id_seq;
DROP TABLE IF EXISTS public.library_borrow_records;
DROP SEQUENCE IF EXISTS public.jobs_id_seq;
DROP TABLE IF EXISTS public.jobs;
DROP TABLE IF EXISTS public.job_batches;
DROP SEQUENCE IF EXISTS public.jamb_records_id_seq;
DROP TABLE IF EXISTS public.jamb_records;
DROP SEQUENCE IF EXISTS public.invoices_id_seq;
DROP TABLE IF EXISTS public.invoices;
DROP SEQUENCE IF EXISTS public.invoice_items_id_seq;
DROP TABLE IF EXISTS public.invoice_items;
DROP SEQUENCE IF EXISTS public.hostels_id_seq;
DROP TABLE IF EXISTS public.hostels;
DROP SEQUENCE IF EXISTS public.hostel_rooms_id_seq;
DROP TABLE IF EXISTS public.hostel_rooms;
DROP SEQUENCE IF EXISTS public.hostel_maintenance_tickets_id_seq;
DROP TABLE IF EXISTS public.hostel_maintenance_tickets;
DROP SEQUENCE IF EXISTS public.hostel_allocations_id_seq;
DROP TABLE IF EXISTS public.hostel_allocations;
DROP SEQUENCE IF EXISTS public.failed_jobs_id_seq;
DROP TABLE IF EXISTS public.failed_jobs;
DROP SEQUENCE IF EXISTS public.faculties_id_seq;
DROP TABLE IF EXISTS public.faculties;
DROP SEQUENCE IF EXISTS public.digital_id_cards_id_seq;
DROP TABLE IF EXISTS public.digital_id_cards;
DROP SEQUENCE IF EXISTS public.departments_id_seq;
DROP TABLE IF EXISTS public.departments;
DROP SEQUENCE IF EXISTS public.courses_id_seq;
DROP TABLE IF EXISTS public.courses;
DROP SEQUENCE IF EXISTS public.course_results_id_seq;
DROP TABLE IF EXISTS public.course_results;
DROP SEQUENCE IF EXISTS public.course_registrations_id_seq;
DROP TABLE IF EXISTS public.course_registrations;
DROP SEQUENCE IF EXISTS public.course_registration_items_id_seq;
DROP TABLE IF EXISTS public.course_registration_items;
DROP SEQUENCE IF EXISTS public.course_materials_id_seq;
DROP TABLE IF EXISTS public.course_materials;
DROP SEQUENCE IF EXISTS public.clinic_registrations_id_seq;
DROP TABLE IF EXISTS public.clinic_registrations;
DROP SEQUENCE IF EXISTS public.clinic_appointments_id_seq;
DROP TABLE IF EXISTS public.clinic_appointments;
DROP SEQUENCE IF EXISTS public.cbt_student_sessions_id_seq;
DROP TABLE IF EXISTS public.cbt_student_sessions;
DROP SEQUENCE IF EXISTS public.cbt_questions_id_seq;
DROP TABLE IF EXISTS public.cbt_questions;
DROP SEQUENCE IF EXISTS public.cbt_exams_id_seq;
DROP TABLE IF EXISTS public.cbt_exams;
DROP SEQUENCE IF EXISTS public.career_jobs_id_seq;
DROP TABLE IF EXISTS public.career_jobs;
DROP TABLE IF EXISTS public.cache_locks;
DROP TABLE IF EXISTS public.cache;
DROP SEQUENCE IF EXISTS public.bed_spaces_id_seq;
DROP TABLE IF EXISTS public.bed_spaces;
DROP SEQUENCE IF EXISTS public.assignments_id_seq;
DROP TABLE IF EXISTS public.assignments;
DROP SEQUENCE IF EXISTS public.assignment_submissions_id_seq;
DROP TABLE IF EXISTS public.assignment_submissions;
DROP SEQUENCE IF EXISTS public.approval_steps_id_seq;
DROP TABLE IF EXISTS public.approval_steps;
DROP SEQUENCE IF EXISTS public.approval_requests_id_seq;
DROP TABLE IF EXISTS public.approval_requests;
DROP SEQUENCE IF EXISTS public.ai_messages_id_seq;
DROP TABLE IF EXISTS public.ai_messages;
DROP SEQUENCE IF EXISTS public.ai_conversations_id_seq;
DROP TABLE IF EXISTS public.ai_conversations;
DROP SEQUENCE IF EXISTS public.admissions_applications_id_seq;
DROP TABLE IF EXISTS public.admissions_applications;
SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: admissions_applications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.admissions_applications (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    user_id bigint NOT NULL,
    application_no character varying(30) NOT NULL,
    first_choice_programme_id bigint,
    second_choice_programme_id bigint,
    status character varying(30) DEFAULT 'draft'::character varying NOT NULL,
    app_fee_paid boolean DEFAULT false NOT NULL,
    screening_score numeric(5,2),
    offer_accepted boolean DEFAULT false NOT NULL,
    acceptance_fee_paid boolean DEFAULT false NOT NULL,
    profile_complete boolean DEFAULT false NOT NULL,
    documents_verified boolean DEFAULT false NOT NULL,
    matric_number_issued character varying(30),
    uploaded_documents json,
    steps_completed json,
    submitted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: admissions_applications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.admissions_applications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: admissions_applications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.admissions_applications_id_seq OWNED BY public.admissions_applications.id;


--
-- Name: ai_conversations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ai_conversations (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    user_id bigint NOT NULL,
    session_id character varying(80) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: ai_conversations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ai_conversations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ai_conversations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ai_conversations_id_seq OWNED BY public.ai_conversations.id;


--
-- Name: ai_messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ai_messages (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    conversation_id bigint NOT NULL,
    role character varying(20) NOT NULL,
    content text NOT NULL,
    tokens_used integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: ai_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ai_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ai_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ai_messages_id_seq OWNED BY public.ai_messages.id;


--
-- Name: approval_requests; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.approval_requests (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    request_ref character varying(50) NOT NULL,
    type character varying(40) NOT NULL,
    title character varying(255) NOT NULL,
    reason text NOT NULL,
    supporting_document_url character varying(255),
    total_levels integer DEFAULT 2 NOT NULL,
    current_level integer DEFAULT 1 NOT NULL,
    status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    metadata json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: approval_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.approval_requests_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: approval_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.approval_requests_id_seq OWNED BY public.approval_requests.id;


--
-- Name: approval_steps; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.approval_steps (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    approval_request_id bigint NOT NULL,
    assigned_staff_id bigint,
    required_role character varying(50) NOT NULL,
    level_number integer DEFAULT 1 NOT NULL,
    action character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    comments text,
    acted_by_user_id bigint,
    acted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: approval_steps_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.approval_steps_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: approval_steps_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.approval_steps_id_seq OWNED BY public.approval_steps.id;


--
-- Name: assignment_submissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.assignment_submissions (
    id bigint NOT NULL,
    assignment_id bigint NOT NULL,
    student_id bigint NOT NULL,
    submission_url character varying(500),
    student_comment text,
    submitted_at timestamp(0) without time zone NOT NULL,
    score numeric(5,2),
    feedback text,
    graded_by_staff_id bigint,
    graded_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: assignment_submissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.assignment_submissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: assignment_submissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.assignment_submissions_id_seq OWNED BY public.assignment_submissions.id;


--
-- Name: assignments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.assignments (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    course_id bigint NOT NULL,
    creator_staff_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    instructions text NOT NULL,
    attachment_url character varying(500),
    due_date timestamp(0) without time zone NOT NULL,
    max_score numeric(5,2) DEFAULT '30'::numeric NOT NULL,
    is_published boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: assignments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.assignments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: assignments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.assignments_id_seq OWNED BY public.assignments.id;


--
-- Name: bed_spaces; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bed_spaces (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    hostel_room_id bigint NOT NULL,
    bed_label character varying(20) NOT NULL,
    status character varying(20) DEFAULT 'available'::character varying NOT NULL,
    lock_token character varying(64),
    locked_until timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bed_spaces_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bed_spaces_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bed_spaces_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bed_spaces_id_seq OWNED BY public.bed_spaces.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: career_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.career_jobs (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    company character varying(255) NOT NULL,
    job_type character varying(30) DEFAULT 'internship'::character varying NOT NULL,
    location character varying(255),
    salary_range character varying(255),
    description text,
    requirements json,
    contact_email character varying(255),
    deadline date,
    apply_url character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: career_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.career_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: career_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.career_jobs_id_seq OWNED BY public.career_jobs.id;


--
-- Name: cbt_exams; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cbt_exams (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    course_id bigint NOT NULL,
    created_by_staff_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    instructions text,
    duration_minutes smallint DEFAULT '30'::smallint NOT NULL,
    total_marks numeric(5,2) DEFAULT '30'::numeric NOT NULL,
    pass_percentage numeric(5,2) DEFAULT '50'::numeric NOT NULL,
    start_time timestamp(0) without time zone,
    end_time timestamp(0) without time zone,
    shuffle_questions boolean DEFAULT true NOT NULL,
    shuffle_options boolean DEFAULT true NOT NULL,
    show_result_immediately boolean DEFAULT true NOT NULL,
    is_published boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cbt_exams_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cbt_exams_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cbt_exams_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cbt_exams_id_seq OWNED BY public.cbt_exams.id;


--
-- Name: cbt_questions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cbt_questions (
    id bigint NOT NULL,
    cbt_exam_id bigint NOT NULL,
    question_text text NOT NULL,
    question_type character varying(255) DEFAULT 'single_choice'::character varying NOT NULL,
    options json NOT NULL,
    correct_answer character varying(255) NOT NULL,
    points numeric(4,2) DEFAULT '1'::numeric NOT NULL,
    explanation text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT cbt_questions_question_type_check CHECK (((question_type)::text = ANY ((ARRAY['single_choice'::character varying, 'multiple_choice'::character varying, 'true_false'::character varying])::text[])))
);


--
-- Name: cbt_questions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cbt_questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cbt_questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cbt_questions_id_seq OWNED BY public.cbt_questions.id;


--
-- Name: cbt_student_sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cbt_student_sessions (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    cbt_exam_id bigint NOT NULL,
    student_id bigint NOT NULL,
    started_at timestamp(0) without time zone NOT NULL,
    expires_at timestamp(0) without time zone NOT NULL,
    submitted_at timestamp(0) without time zone,
    answers json,
    score_obtained numeric(5,2),
    percentage numeric(5,2),
    status character varying(255) DEFAULT 'in_progress'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT cbt_student_sessions_status_check CHECK (((status)::text = ANY ((ARRAY['in_progress'::character varying, 'completed'::character varying, 'timed_out'::character varying])::text[])))
);


--
-- Name: cbt_student_sessions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cbt_student_sessions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cbt_student_sessions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cbt_student_sessions_id_seq OWNED BY public.cbt_student_sessions.id;


--
-- Name: clinic_appointments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clinic_appointments (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    doctor_staff_id bigint,
    visit_date date NOT NULL,
    symptoms text NOT NULL,
    diagnosis text,
    prescription text,
    doctor_notes text,
    status character varying(20) DEFAULT 'scheduled'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: clinic_appointments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clinic_appointments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clinic_appointments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clinic_appointments_id_seq OWNED BY public.clinic_appointments.id;


--
-- Name: clinic_registrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clinic_registrations (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    hospital_number character varying(50) NOT NULL,
    blood_group character varying(10) NOT NULL,
    genotype character varying(10) NOT NULL,
    allergies text,
    chronic_conditions text,
    emergency_contact_name character varying(255) NOT NULL,
    emergency_contact_phone character varying(30) NOT NULL,
    emergency_contact_relation character varying(50) DEFAULT 'Parent/Guardian'::character varying NOT NULL,
    is_cleared boolean DEFAULT true NOT NULL,
    registered_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: clinic_registrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clinic_registrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clinic_registrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clinic_registrations_id_seq OWNED BY public.clinic_registrations.id;


--
-- Name: course_materials; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_materials (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    course_id bigint NOT NULL,
    uploader_staff_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    file_url character varying(500) NOT NULL,
    file_type character varying(50) DEFAULT 'pdf'::character varying NOT NULL,
    file_size_kb integer DEFAULT 0 NOT NULL,
    week_number smallint DEFAULT '1'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: course_materials_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.course_materials_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: course_materials_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.course_materials_id_seq OWNED BY public.course_materials.id;


--
-- Name: course_registration_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_registration_items (
    id bigint NOT NULL,
    course_registration_id bigint NOT NULL,
    course_id bigint NOT NULL,
    status character varying(255) DEFAULT 'registered'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT course_registration_items_status_check CHECK (((status)::text = ANY ((ARRAY['registered'::character varying, 'dropped'::character varying])::text[])))
);


--
-- Name: course_registration_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.course_registration_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: course_registration_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.course_registration_items_id_seq OWNED BY public.course_registration_items.id;


--
-- Name: course_registrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_registrations (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    academic_session character varying(15) DEFAULT '2025/2026'::character varying NOT NULL,
    semester character varying(255) DEFAULT 'first'::character varying NOT NULL,
    total_credits smallint DEFAULT '0'::smallint NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    approved_by_staff_id bigint,
    rejection_reason text,
    approved_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT course_registrations_semester_check CHECK (((semester)::text = ANY ((ARRAY['first'::character varying, 'second'::character varying])::text[]))),
    CONSTRAINT course_registrations_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'submitted'::character varying, 'approved'::character varying, 'rejected'::character varying])::text[])))
);


--
-- Name: course_registrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.course_registrations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: course_registrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.course_registrations_id_seq OWNED BY public.course_registrations.id;


--
-- Name: course_results; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.course_results (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    course_id bigint NOT NULL,
    academic_session character varying(15) DEFAULT '2025/2026'::character varying NOT NULL,
    semester character varying(255) DEFAULT 'first'::character varying NOT NULL,
    ca_score numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    exam_score numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    total_score numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    grade character varying(255) DEFAULT 'F'::character varying NOT NULL,
    grade_point numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    credit_points numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(255) DEFAULT 'draft'::character varying NOT NULL,
    uploaded_by_staff_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT course_results_grade_check CHECK (((grade)::text = ANY ((ARRAY['A'::character varying, 'B'::character varying, 'C'::character varying, 'D'::character varying, 'F'::character varying])::text[]))),
    CONSTRAINT course_results_semester_check CHECK (((semester)::text = ANY ((ARRAY['first'::character varying, 'second'::character varying])::text[]))),
    CONSTRAINT course_results_status_check CHECK (((status)::text = ANY ((ARRAY['draft'::character varying, 'submitted'::character varying, 'approved_by_hod'::character varying, 'published'::character varying])::text[])))
);


--
-- Name: course_results_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.course_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: course_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.course_results_id_seq OWNED BY public.course_results.id;


--
-- Name: courses; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.courses (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    department_id bigint NOT NULL,
    code character varying(20) NOT NULL,
    title character varying(255) NOT NULL,
    credit_units smallint DEFAULT '3'::smallint NOT NULL,
    level character varying(10) DEFAULT '100L'::character varying NOT NULL,
    semester character varying(255) DEFAULT 'first'::character varying NOT NULL,
    is_elective boolean DEFAULT false NOT NULL,
    prerequisite_course_id bigint,
    description text,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT courses_semester_check CHECK (((semester)::text = ANY ((ARRAY['first'::character varying, 'second'::character varying])::text[])))
);


--
-- Name: courses_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.courses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: courses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.courses_id_seq OWNED BY public.courses.id;


--
-- Name: departments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.departments (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    faculty_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(10) NOT NULL,
    description text,
    hod_staff_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: departments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.departments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: departments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.departments_id_seq OWNED BY public.departments.id;


--
-- Name: digital_id_cards; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.digital_id_cards (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    card_number character varying(50) NOT NULL,
    barcode_hash character varying(100) NOT NULL,
    qr_payload text,
    issue_date date NOT NULL,
    expiry_date date NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: digital_id_cards_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.digital_id_cards_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: digital_id_cards_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.digital_id_cards_id_seq OWNED BY public.digital_id_cards.id;


--
-- Name: faculties; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.faculties (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(10) NOT NULL,
    description text,
    dean_staff_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: faculties_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.faculties_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: faculties_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.faculties_id_seq OWNED BY public.faculties.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: hostel_allocations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hostel_allocations (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    bed_space_id bigint NOT NULL,
    academic_session character varying(20) NOT NULL,
    allocation_ref character varying(50) NOT NULL,
    status character varying(20) DEFAULT 'allocated'::character varying NOT NULL,
    rules_agreed boolean DEFAULT false NOT NULL,
    rules_agreed_at timestamp(0) without time zone,
    check_in_date date,
    check_out_date date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hostel_allocations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hostel_allocations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hostel_allocations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hostel_allocations_id_seq OWNED BY public.hostel_allocations.id;


--
-- Name: hostel_maintenance_tickets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hostel_maintenance_tickets (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    hostel_room_id bigint NOT NULL,
    ticket_number character varying(50) NOT NULL,
    category character varying(30) DEFAULT 'other'::character varying NOT NULL,
    priority character varying(20) DEFAULT 'medium'::character varying NOT NULL,
    description text NOT NULL,
    status character varying(20) DEFAULT 'open'::character varying NOT NULL,
    resolution_notes text,
    resolved_by_staff_id bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hostel_maintenance_tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hostel_maintenance_tickets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hostel_maintenance_tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hostel_maintenance_tickets_id_seq OWNED BY public.hostel_maintenance_tickets.id;


--
-- Name: hostel_rooms; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hostel_rooms (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    hostel_id bigint NOT NULL,
    room_number character varying(20) NOT NULL,
    block_name character varying(20),
    floor integer DEFAULT 1 NOT NULL,
    room_type character varying(30) DEFAULT 'quad'::character varying NOT NULL,
    capacity integer DEFAULT 4 NOT NULL,
    allocated_count integer DEFAULT 0 NOT NULL,
    fee_amount numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hostel_rooms_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hostel_rooms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hostel_rooms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hostel_rooms_id_seq OWNED BY public.hostel_rooms.id;


--
-- Name: hostels; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.hostels (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(30) NOT NULL,
    gender character varying(15) DEFAULT 'mixed'::character varying NOT NULL,
    campus_location character varying(255),
    master_staff_id bigint,
    capacity integer DEFAULT 0 NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: hostels_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.hostels_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: hostels_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.hostels_id_seq OWNED BY public.hostels.id;


--
-- Name: invoice_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoice_items (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    invoice_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    is_paid boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: invoice_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoice_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoice_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoice_items_id_seq OWNED BY public.invoice_items.id;


--
-- Name: invoices; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.invoices (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    user_id bigint NOT NULL,
    invoice_number character varying(50) NOT NULL,
    title character varying(255) NOT NULL,
    fee_type character varying(40) DEFAULT 'tuition'::character varying NOT NULL,
    academic_session character varying(20) NOT NULL,
    semester character varying(20),
    base_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    platform_fee numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    amount_paid numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(20) DEFAULT 'unpaid'::character varying NOT NULL,
    due_date date,
    installment_allowed boolean DEFAULT false NOT NULL,
    installment_plan json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: invoices_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.invoices_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: invoices_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.invoices_id_seq OWNED BY public.invoices.id;


--
-- Name: jamb_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jamb_records (
    id bigint NOT NULL,
    application_id bigint NOT NULL,
    reg_number character varying(30) NOT NULL,
    score smallint NOT NULL,
    year smallint NOT NULL,
    institution_chosen character varying(255),
    course_chosen character varying(255),
    verified boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: jamb_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jamb_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jamb_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jamb_records_id_seq OWNED BY public.jamb_records.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: library_borrow_records; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.library_borrow_records (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    library_item_id bigint NOT NULL,
    borrow_date date NOT NULL,
    due_date date NOT NULL,
    return_date date,
    fine_amount numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    status character varying(20) DEFAULT 'reserved'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: library_borrow_records_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.library_borrow_records_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: library_borrow_records_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.library_borrow_records_id_seq OWNED BY public.library_borrow_records.id;


--
-- Name: library_items; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.library_items (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    isbn character varying(30),
    title character varying(255) NOT NULL,
    author character varying(255) NOT NULL,
    category character varying(40) DEFAULT 'textbook'::character varying NOT NULL,
    faculty character varying(255),
    department character varying(255),
    edition character varying(20),
    year integer,
    total_copies integer DEFAULT 1 NOT NULL,
    available_copies integer DEFAULT 1 NOT NULL,
    shelf_location character varying(255),
    is_digital boolean DEFAULT false NOT NULL,
    digital_download_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: library_items_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.library_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: library_items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.library_items_id_seq OWNED BY public.library_items.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: olevel_results; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.olevel_results (
    id bigint NOT NULL,
    application_id bigint NOT NULL,
    sitting_number smallint DEFAULT '1'::smallint NOT NULL,
    exam_type character varying(20) DEFAULT 'WAEC'::character varying NOT NULL,
    year smallint NOT NULL,
    exam_number character varying(30) NOT NULL,
    centre_number character varying(30),
    subjects json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: olevel_results_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.olevel_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: olevel_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.olevel_results_id_seq OWNED BY public.olevel_results.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payments (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    invoice_id bigint NOT NULL,
    user_id bigint NOT NULL,
    transaction_reference character varying(80) NOT NULL,
    amount numeric(12,2) NOT NULL,
    platform_fee numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    university_amount numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    gateway character varying(30) DEFAULT 'paystack'::character varying NOT NULL,
    gateway_reference character varying(100),
    status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    receipt_number character varying(50),
    split_code character varying(100),
    raw_webhook_payload json,
    paid_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payments_id_seq OWNED BY public.payments.id;


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: pg_proposals; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pg_proposals (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    abstract text NOT NULL,
    document_url character varying(255),
    status character varying(30) DEFAULT 'submitted'::character varying NOT NULL,
    reviewer_feedback text,
    defense_date date,
    defense_score numeric(5,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pg_proposals_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pg_proposals_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pg_proposals_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pg_proposals_id_seq OWNED BY public.pg_proposals.id;


--
-- Name: pg_student_profiles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pg_student_profiles (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    programme_type character varying(20) DEFAULT 'Ph.D.'::character varying NOT NULL,
    research_title character varying(255),
    primary_supervisor_staff_id bigint,
    co_supervisor_staff_id bigint,
    current_stage character varying(40) DEFAULT 'coursework'::character varying NOT NULL,
    expected_graduation_date date,
    risk_score integer DEFAULT 15 NOT NULL,
    risk_level character varying(20) DEFAULT 'low'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pg_student_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pg_student_profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pg_student_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pg_student_profiles_id_seq OWNED BY public.pg_student_profiles.id;


--
-- Name: pg_supervision_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pg_supervision_logs (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    staff_id bigint NOT NULL,
    meeting_date date NOT NULL,
    summary_notes text NOT NULL,
    next_deliverables text NOT NULL,
    status character varying(30) DEFAULT 'pending_approval'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pg_supervision_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pg_supervision_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pg_supervision_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pg_supervision_logs_id_seq OWNED BY public.pg_supervision_logs.id;


--
-- Name: pg_thesis_milestones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pg_thesis_milestones (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    chapter_number integer NOT NULL,
    title character varying(255) NOT NULL,
    status character varying(30) DEFAULT 'pending'::character varying NOT NULL,
    submission_url character varying(255),
    supervisor_comments text,
    submitted_at timestamp(0) without time zone,
    reviewed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pg_thesis_milestones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pg_thesis_milestones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pg_thesis_milestones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pg_thesis_milestones_id_seq OWNED BY public.pg_thesis_milestones.id;


--
-- Name: post_utme_slots; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.post_utme_slots (
    id bigint NOT NULL,
    application_id bigint NOT NULL,
    exam_date date NOT NULL,
    exam_time character varying(20) NOT NULL,
    venue character varying(100) NOT NULL,
    seat_number character varying(20),
    is_attended boolean DEFAULT false NOT NULL,
    score_obtained numeric(5,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: post_utme_slots_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.post_utme_slots_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: post_utme_slots_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.post_utme_slots_id_seq OWNED BY public.post_utme_slots.id;


--
-- Name: programmes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.programmes (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    department_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(10) NOT NULL,
    degree character varying(20) DEFAULT 'B.Sc.'::character varying NOT NULL,
    duration_years smallint DEFAULT '4'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: programmes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.programmes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: programmes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.programmes_id_seq OWNED BY public.programmes.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: semester_results; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.semester_results (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    academic_session character varying(15) DEFAULT '2025/2026'::character varying NOT NULL,
    semester character varying(255) DEFAULT 'first'::character varying NOT NULL,
    level character varying(10) DEFAULT '100L'::character varying NOT NULL,
    total_credits_registered smallint DEFAULT '0'::smallint NOT NULL,
    total_credits_earned smallint DEFAULT '0'::smallint NOT NULL,
    total_grade_points numeric(6,2) DEFAULT '0'::numeric NOT NULL,
    gpa numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    cumulative_credits_registered smallint DEFAULT '0'::smallint NOT NULL,
    cumulative_credits_earned smallint DEFAULT '0'::smallint NOT NULL,
    cumulative_grade_points numeric(7,2) DEFAULT '0'::numeric NOT NULL,
    cgpa numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    standing character varying(50) DEFAULT 'Good Standing'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT semester_results_semester_check CHECK (((semester)::text = ANY ((ARRAY['first'::character varying, 'second'::character varying])::text[])))
);


--
-- Name: semester_results_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.semester_results_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: semester_results_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.semester_results_id_seq OWNED BY public.semester_results.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: staff; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.staff (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    user_id bigint NOT NULL,
    staff_no character varying(30) NOT NULL,
    department_id bigint,
    faculty_id bigint,
    title character varying(20) DEFAULT 'Dr.'::character varying NOT NULL,
    designation character varying(100) NOT NULL,
    rank character varying(50) DEFAULT 'Lecturer'::character varying NOT NULL,
    initials character varying(10),
    color character varying(15) DEFAULT '#2E5FA3'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: staff_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.staff_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: staff_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.staff_id_seq OWNED BY public.staff.id;


--
-- Name: student_certifications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_certifications (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    issuer character varying(255) NOT NULL,
    issue_date date,
    credential_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: student_certifications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_certifications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_certifications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_certifications_id_seq OWNED BY public.student_certifications.id;


--
-- Name: student_profiles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_profiles (
    id bigint NOT NULL,
    student_id bigint NOT NULL,
    dob date,
    gender character varying(255) DEFAULT 'male'::character varying NOT NULL,
    state_of_origin character varying(50),
    lga character varying(50),
    nationality character varying(50) DEFAULT 'Nigerian'::character varying NOT NULL,
    address text,
    city character varying(50),
    state character varying(50),
    father_name character varying(255),
    father_phone character varying(255),
    father_occupation character varying(255),
    mother_name character varying(255),
    mother_phone character varying(255),
    mother_occupation character varying(255),
    next_of_kin_name character varying(255),
    next_of_kin_relationship character varying(255),
    next_of_kin_phone character varying(255),
    blood_group character varying(5),
    genotype character varying(5),
    allergies text,
    medical_conditions text,
    current_medications text,
    has_disability boolean DEFAULT false NOT NULL,
    religion character varying(30),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT student_profiles_gender_check CHECK (((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying, 'other'::character varying])::text[])))
);


--
-- Name: student_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_profiles_id_seq OWNED BY public.student_profiles.id;


--
-- Name: student_projects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_projects (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    technologies json,
    github_url character varying(255),
    live_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: student_projects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_projects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_projects_id_seq OWNED BY public.student_projects.id;


--
-- Name: student_skills; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.student_skills (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    student_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    category character varying(40) DEFAULT 'technical'::character varying NOT NULL,
    proficiency_level character varying(30) DEFAULT 'intermediate'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: student_skills_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.student_skills_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: student_skills_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.student_skills_id_seq OWNED BY public.student_skills.id;


--
-- Name: students; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.students (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    user_id bigint NOT NULL,
    matric_number character varying(30) NOT NULL,
    jamb_reg_no character varying(30),
    faculty_id bigint NOT NULL,
    department_id bigint NOT NULL,
    programme_id bigint NOT NULL,
    level character varying(10) DEFAULT '100L'::character varying NOT NULL,
    academic_session character varying(15) DEFAULT '2025/2026'::character varying NOT NULL,
    current_semester character varying(255) DEFAULT 'first'::character varying NOT NULL,
    entry_mode character varying(255) DEFAULT 'UTME'::character varying NOT NULL,
    cgpa numeric(3,2) DEFAULT '0'::numeric NOT NULL,
    standing character varying(50) DEFAULT 'Good Standing'::character varying NOT NULL,
    digital_id_token character varying(64),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT students_current_semester_check CHECK (((current_semester)::text = ANY ((ARRAY['first'::character varying, 'second'::character varying])::text[]))),
    CONSTRAINT students_entry_mode_check CHECK (((entry_mode)::text = ANY ((ARRAY['UTME'::character varying, 'Direct Entry'::character varying, 'Transfer'::character varying, 'PG'::character varying])::text[])))
);


--
-- Name: students_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.students_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: students_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.students_id_seq OWNED BY public.students.id;


--
-- Name: study_group_members; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.study_group_members (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    study_group_id bigint NOT NULL,
    student_id bigint NOT NULL,
    role character varying(20) DEFAULT 'member'::character varying NOT NULL,
    joined_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: study_group_members_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.study_group_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: study_group_members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.study_group_members_id_seq OWNED BY public.study_group_members.id;


--
-- Name: study_group_messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.study_group_messages (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    study_group_id bigint NOT NULL,
    student_id bigint NOT NULL,
    message text NOT NULL,
    attachment_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: study_group_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.study_group_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: study_group_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.study_group_messages_id_seq OWNED BY public.study_group_messages.id;


--
-- Name: study_groups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.study_groups (
    id bigint NOT NULL,
    university_id bigint NOT NULL,
    course_id bigint,
    creator_student_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    max_members integer DEFAULT 8 NOT NULL,
    meeting_schedule character varying(255),
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: study_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.study_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: study_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.study_groups_id_seq OWNED BY public.study_groups.id;


--
-- Name: universities; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.universities (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    code character varying(20) NOT NULL,
    subdomain character varying(100) NOT NULL,
    custom_domain character varying(255),
    logo_url character varying(500),
    primary_color character varying(15) DEFAULT '#2E5FA3'::character varying NOT NULL,
    secondary_color character varying(15) DEFAULT '#1A7A6E'::character varying NOT NULL,
    contact_email character varying(255),
    contact_phone character varying(255),
    address text,
    paystack_subaccount_code character varying(100),
    remita_merchant_id character varying(100),
    platform_fee_amount numeric(10,2) DEFAULT '1500'::numeric NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: universities_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.universities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: universities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.universities_id_seq OWNED BY public.universities.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    university_id bigint,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    phone character varying(255),
    user_type character varying(255) DEFAULT 'student'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    avatar_url character varying(255),
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: admissions_applications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admissions_applications ALTER COLUMN id SET DEFAULT nextval('public.admissions_applications_id_seq'::regclass);


--
-- Name: ai_conversations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_conversations ALTER COLUMN id SET DEFAULT nextval('public.ai_conversations_id_seq'::regclass);


--
-- Name: ai_messages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_messages ALTER COLUMN id SET DEFAULT nextval('public.ai_messages_id_seq'::regclass);


--
-- Name: approval_requests id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_requests ALTER COLUMN id SET DEFAULT nextval('public.approval_requests_id_seq'::regclass);


--
-- Name: approval_steps id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_steps ALTER COLUMN id SET DEFAULT nextval('public.approval_steps_id_seq'::regclass);


--
-- Name: assignment_submissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignment_submissions ALTER COLUMN id SET DEFAULT nextval('public.assignment_submissions_id_seq'::regclass);


--
-- Name: assignments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignments ALTER COLUMN id SET DEFAULT nextval('public.assignments_id_seq'::regclass);


--
-- Name: bed_spaces id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bed_spaces ALTER COLUMN id SET DEFAULT nextval('public.bed_spaces_id_seq'::regclass);


--
-- Name: career_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.career_jobs ALTER COLUMN id SET DEFAULT nextval('public.career_jobs_id_seq'::regclass);


--
-- Name: cbt_exams id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_exams ALTER COLUMN id SET DEFAULT nextval('public.cbt_exams_id_seq'::regclass);


--
-- Name: cbt_questions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_questions ALTER COLUMN id SET DEFAULT nextval('public.cbt_questions_id_seq'::regclass);


--
-- Name: cbt_student_sessions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_student_sessions ALTER COLUMN id SET DEFAULT nextval('public.cbt_student_sessions_id_seq'::regclass);


--
-- Name: clinic_appointments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_appointments ALTER COLUMN id SET DEFAULT nextval('public.clinic_appointments_id_seq'::regclass);


--
-- Name: clinic_registrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_registrations ALTER COLUMN id SET DEFAULT nextval('public.clinic_registrations_id_seq'::regclass);


--
-- Name: course_materials id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_materials ALTER COLUMN id SET DEFAULT nextval('public.course_materials_id_seq'::regclass);


--
-- Name: course_registration_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registration_items ALTER COLUMN id SET DEFAULT nextval('public.course_registration_items_id_seq'::regclass);


--
-- Name: course_registrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registrations ALTER COLUMN id SET DEFAULT nextval('public.course_registrations_id_seq'::regclass);


--
-- Name: course_results id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_results ALTER COLUMN id SET DEFAULT nextval('public.course_results_id_seq'::regclass);


--
-- Name: courses id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses ALTER COLUMN id SET DEFAULT nextval('public.courses_id_seq'::regclass);


--
-- Name: departments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments ALTER COLUMN id SET DEFAULT nextval('public.departments_id_seq'::regclass);


--
-- Name: digital_id_cards id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.digital_id_cards ALTER COLUMN id SET DEFAULT nextval('public.digital_id_cards_id_seq'::regclass);


--
-- Name: faculties id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.faculties ALTER COLUMN id SET DEFAULT nextval('public.faculties_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: hostel_allocations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_allocations ALTER COLUMN id SET DEFAULT nextval('public.hostel_allocations_id_seq'::regclass);


--
-- Name: hostel_maintenance_tickets id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_maintenance_tickets ALTER COLUMN id SET DEFAULT nextval('public.hostel_maintenance_tickets_id_seq'::regclass);


--
-- Name: hostel_rooms id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_rooms ALTER COLUMN id SET DEFAULT nextval('public.hostel_rooms_id_seq'::regclass);


--
-- Name: hostels id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostels ALTER COLUMN id SET DEFAULT nextval('public.hostels_id_seq'::regclass);


--
-- Name: invoice_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_items ALTER COLUMN id SET DEFAULT nextval('public.invoice_items_id_seq'::regclass);


--
-- Name: invoices id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices ALTER COLUMN id SET DEFAULT nextval('public.invoices_id_seq'::regclass);


--
-- Name: jamb_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jamb_records ALTER COLUMN id SET DEFAULT nextval('public.jamb_records_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: library_borrow_records id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_borrow_records ALTER COLUMN id SET DEFAULT nextval('public.library_borrow_records_id_seq'::regclass);


--
-- Name: library_items id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_items ALTER COLUMN id SET DEFAULT nextval('public.library_items_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: olevel_results id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.olevel_results ALTER COLUMN id SET DEFAULT nextval('public.olevel_results_id_seq'::regclass);


--
-- Name: payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments ALTER COLUMN id SET DEFAULT nextval('public.payments_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: pg_proposals id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_proposals ALTER COLUMN id SET DEFAULT nextval('public.pg_proposals_id_seq'::regclass);


--
-- Name: pg_student_profiles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_student_profiles ALTER COLUMN id SET DEFAULT nextval('public.pg_student_profiles_id_seq'::regclass);


--
-- Name: pg_supervision_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_supervision_logs ALTER COLUMN id SET DEFAULT nextval('public.pg_supervision_logs_id_seq'::regclass);


--
-- Name: pg_thesis_milestones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_thesis_milestones ALTER COLUMN id SET DEFAULT nextval('public.pg_thesis_milestones_id_seq'::regclass);


--
-- Name: post_utme_slots id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.post_utme_slots ALTER COLUMN id SET DEFAULT nextval('public.post_utme_slots_id_seq'::regclass);


--
-- Name: programmes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.programmes ALTER COLUMN id SET DEFAULT nextval('public.programmes_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: semester_results id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_results ALTER COLUMN id SET DEFAULT nextval('public.semester_results_id_seq'::regclass);


--
-- Name: staff id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.staff ALTER COLUMN id SET DEFAULT nextval('public.staff_id_seq'::regclass);


--
-- Name: student_certifications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_certifications ALTER COLUMN id SET DEFAULT nextval('public.student_certifications_id_seq'::regclass);


--
-- Name: student_profiles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_profiles ALTER COLUMN id SET DEFAULT nextval('public.student_profiles_id_seq'::regclass);


--
-- Name: student_projects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_projects ALTER COLUMN id SET DEFAULT nextval('public.student_projects_id_seq'::regclass);


--
-- Name: student_skills id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_skills ALTER COLUMN id SET DEFAULT nextval('public.student_skills_id_seq'::regclass);


--
-- Name: students id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students ALTER COLUMN id SET DEFAULT nextval('public.students_id_seq'::regclass);


--
-- Name: study_group_members id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_members ALTER COLUMN id SET DEFAULT nextval('public.study_group_members_id_seq'::regclass);


--
-- Name: study_group_messages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_messages ALTER COLUMN id SET DEFAULT nextval('public.study_group_messages_id_seq'::regclass);


--
-- Name: study_groups id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_groups ALTER COLUMN id SET DEFAULT nextval('public.study_groups_id_seq'::regclass);


--
-- Name: universities id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.universities ALTER COLUMN id SET DEFAULT nextval('public.universities_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: admissions_applications; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.admissions_applications VALUES (1, 1, 5, 'APP/NVU/2026/00142', 1, 2, 'under_review', true, 78.50, false, false, false, false, NULL, NULL, '{"personal":true,"jamb":true,"olevel":true,"programme":true,"documents":true}', '2026-09-06 13:31:57', '2026-09-05 21:52:30', '2026-09-08 13:31:57');


--
-- Data for Name: ai_conversations; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: ai_messages; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: approval_requests; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.approval_requests VALUES (1, 1, 1, 'REQ-2026-CFS001', 'course_form_signing', 'Semester Course Registration Signing (CSC 300L)', 'Completed full registration of 18 credit units for CSC 300L First Semester.', NULL, 2, 2, 'in_review', NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: approval_steps; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.approval_steps VALUES (1, 1, 1, 1, 'hod', 1, 'approved', 'Course prerequisites verified and credit units meet departmental requirements.', 2, '2026-09-05 11:56:00', '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.approval_steps VALUES (2, 1, 1, 2, 'dean', 2, 'pending', NULL, NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: assignment_submissions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.assignment_submissions VALUES (1, 1, 1, 'https://github.com/chidi/avl-tree-implementation', 'Completed AVL balancing with full unit tests.', '2026-09-07 13:31:58', 28.50, 'Excellent balance rotation logic.', 1, '2026-09-08 13:31:58', '2026-09-05 21:52:30', '2026-09-08 13:31:58');


--
-- Data for Name: assignments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.assignments VALUES (1, 1, 1, 1, 'BST Implementation in PHP/Python', 'Implement a self-balancing AVL tree and submit your GitHub repository link.', NULL, '2026-09-19 21:52:30', 30.00, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: bed_spaces; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.bed_spaces VALUES (1, 1, 1, 'Bed A', 'available', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.bed_spaces VALUES (2, 1, 1, 'Bed B', 'occupied', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.bed_spaces VALUES (3, 1, 1, 'Bed C', 'available', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.bed_spaces VALUES (4, 1, 1, 'Bed D', 'available', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.bed_spaces VALUES (5, 1, 2, 'Bed A', 'available', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.bed_spaces VALUES (6, 1, 2, 'Bed B', 'available', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.bed_spaces VALUES (7, 1, 2, 'Bed C', 'available', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.bed_spaces VALUES (8, 1, 2, 'Bed D', 'available', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: career_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.career_jobs VALUES (1, 1, 'Junior Cloud Backend Engineer (Graduate Intern)', 'NetzerTech Systems Worldwide', 'internship', 'Lagos / Hybrid', '₦250,000 - ₦350,000 / month', 'Seeking ambitious computer science finalists to contribute to high-scale enterprise multi-tenant architectures.', '["Proficiency in Laravel and PostgreSQL","Solid understanding of REST APIs and Git","Minimum CGPA of 3.50\/5.00"]', 'careers@netzertech.com', '2026-11-08', NULL, true, '2026-09-08 13:31:31', '2026-09-08 13:31:31');
INSERT INTO public.career_jobs VALUES (2, 1, 'AI Research Assistant (Doctoral Fellowship)', 'Novica Centre for Intelligent Systems', 'part_time', 'Novica Main Campus, Lagos', '₦180,000 / month + Tuition Waiver', 'Collaborate with faculty leads on federated learning benchmarks, dataset labeling, and edge GPU deployments.', '["Enrolled in Postgraduate or 400L Computing Programme","Python, PyTorch or TensorFlow experience"]', 'sps-research@novicauniversity.edu.ng', '2026-10-08', NULL, true, '2026-09-08 13:31:31', '2026-09-08 13:31:31');


--
-- Data for Name: cbt_exams; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.cbt_exams VALUES (1, 1, 1, 1, 'CSC 301 Mid-Semester Quiz', 'Answer all 3 questions. Duration: 15 minutes. Attempt counts for 10% CA.', 15, 10.00, 50.00, '2026-09-04 21:52:30', '2026-09-10 21:52:30', true, true, true, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: cbt_questions; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.cbt_questions VALUES (1, 1, 'What is the worst-case time complexity of searching in an unbalanced Binary Search Tree?', 'single_choice', '[{"id":"A","text":"O(1)"},{"id":"B","text":"O(log n)"},{"id":"C","text":"O(n)"},{"id":"D","text":"O(n^2)"}]', 'C', 3.50, NULL, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.cbt_questions VALUES (2, 1, 'Which tree traversal visits the root node first?', 'single_choice', '[{"id":"A","text":"In-order Traversal"},{"id":"B","text":"Pre-order Traversal"},{"id":"C","text":"Post-order Traversal"},{"id":"D","text":"Level-order Traversal"}]', 'B', 3.50, NULL, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.cbt_questions VALUES (3, 1, 'A complete binary tree with depth d has at most 2^(d+1) - 1 nodes. Is this true or false?', 'single_choice', '[{"id":"A","text":"True"},{"id":"B","text":"False"}]', 'A', 3.00, NULL, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: cbt_student_sessions; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: clinic_appointments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.clinic_appointments VALUES (1, 1, 1, NULL, '2026-08-25', 'Intermittent fever, joint aches, and mild headache.', 'Uncomplicated Plasmodium falciparum malaria.', 'Tab Artemether/Lumefantrine 80/480mg twice daily for 3 days; Tab Paracetamol 1000mg TID for 3 days.', 'Patient advised to hydrate well and sleep under treated bed net.', 'completed', '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: clinic_registrations; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.clinic_registrations VALUES (1, 1, 1, 'NH-NVU-2021-CSC-001', 'O+', 'AA', 'Penicillin, Dust pollen', 'None', 'Chief Jude Okonkwo', '08023456789', 'Father', true, '2026-04-08 13:31:58', '2026-09-08 11:56:00', '2026-09-08 13:31:58');


--
-- Data for Name: course_materials; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.course_materials VALUES (1, 1, 1, 1, 'Week 1: Introduction to Trees and Binary Search Trees', 'Lecture slides and code snippets covering BST balancing algorithms.', 'https://novica.edu.ng/materials/csc301_week1.pdf', 'pdf', 2048, 1, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: course_registration_items; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.course_registration_items VALUES (1, 1, 1, 'registered', '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_registration_items VALUES (2, 1, 2, 'registered', '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_registration_items VALUES (3, 1, 3, 'registered', '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_registration_items VALUES (4, 1, 4, 'registered', '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_registration_items VALUES (5, 1, 5, 'registered', '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_registration_items VALUES (6, 1, 6, 'registered', '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: course_registrations; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.course_registrations VALUES (1, 1, 1, '2025/2026', 'first', 18, 'approved', 1, NULL, '2026-08-18 13:31:57', '2026-09-05 21:52:30', '2026-09-08 13:31:57');


--
-- Data for Name: course_results; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.course_results VALUES (1, 1, 1, 1, '2025/2026', 'first', 28.00, 52.00, 80.00, 'A', 5.00, 15.00, 'published', 1, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_results VALUES (2, 1, 1, 2, '2025/2026', 'first', 26.00, 46.00, 72.00, 'A', 5.00, 15.00, 'published', 1, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_results VALUES (3, 1, 1, 3, '2025/2026', 'first', 25.00, 41.00, 66.00, 'B', 4.00, 12.00, 'published', 1, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_results VALUES (4, 1, 1, 4, '2025/2026', 'first', 24.00, 40.00, 64.00, 'B', 4.00, 12.00, 'published', 1, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_results VALUES (5, 1, 1, 5, '2025/2026', 'first', 28.00, 48.00, 76.00, 'A', 5.00, 15.00, 'published', 1, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.course_results VALUES (6, 1, 1, 6, '2025/2026', 'first', 22.00, 36.00, 58.00, 'C', 3.00, 9.00, 'published', 1, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: courses; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.courses VALUES (1, 1, 1, 'CSC 301', 'Data Structures & Algorithms', 3, '300L', 'first', false, NULL, NULL, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.courses VALUES (2, 1, 1, 'CSC 303', 'Operating Systems Architecture', 3, '300L', 'first', false, NULL, NULL, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.courses VALUES (3, 1, 1, 'CSC 305', 'Database Management Systems', 3, '300L', 'first', false, NULL, NULL, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.courses VALUES (4, 1, 1, 'CSC 307', 'Web Application Frameworks', 3, '300L', 'first', false, NULL, NULL, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.courses VALUES (5, 1, 1, 'CSC 309', 'Software Engineering Principles', 3, '300L', 'first', false, NULL, NULL, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.courses VALUES (6, 1, 1, 'MTH 301', 'Numerical Analysis', 3, '300L', 'first', false, NULL, NULL, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.courses VALUES (7, 2, 3, 'SWE 201', 'Software Requirements & Architecture', 3, '200L', 'first', false, NULL, NULL, true, '2026-09-05 21:52:31', '2026-09-05 21:52:31');


--
-- Data for Name: departments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.departments VALUES (2, 1, 1, 'Department of Information Technology', 'IFT', NULL, NULL, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.departments VALUES (1, 1, 1, 'Department of Computer Science', 'CSC', NULL, 1, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.departments VALUES (3, 2, 3, 'Department of Software Engineering', 'SWE', NULL, NULL, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: digital_id_cards; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.digital_id_cards VALUES (1, 1, 1, 'NVU-ID-2026-00001', '2abf0ed5091ccf02f28a9104b4e2823914fb40ed6977a09854a382b8a4bb53b7', 'http://localhost:8000/api/v1/verify/id-card/2abf0ed5091ccf02f28a9104b4e2823914fb40ed6977a09854a382b8a4bb53b7', '2026-09-08', '2030-09-08', true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: faculties; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.faculties VALUES (2, 1, 'Faculty of Engineering', 'ENG', 'Electrical and Mechanical Engineering', NULL, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.faculties VALUES (1, 1, 'Faculty of Computing & Information Technology', 'CIT', 'Computing and Software Sciences', 2, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.faculties VALUES (3, 2, 'Faculty of Engineering & Emerging Technologies', 'ENG', NULL, NULL, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: hostel_allocations; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.hostel_allocations VALUES (1, 1, 1, 2, '2025/2026', 'KHC-2026-204-CHI', 'confirmed', true, '2026-07-08 11:56:00', '2026-07-08', '2027-03-08', '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: hostel_maintenance_tickets; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.hostel_maintenance_tickets VALUES (1, 1, 1, 1, 'TKT-20260215-PLM1', 'plumbing', 'medium', 'Bathroom shower knob is leaking and dripping water continuously.', 'open', NULL, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: hostel_rooms; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.hostel_rooms VALUES (1, 1, 1, '204', 'Block C', 2, 'quad', 4, 1, 45000.00, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.hostel_rooms VALUES (2, 1, 1, '205', 'Block C', 2, 'quad', 4, 0, 45000.00, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: hostels; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.hostels VALUES (1, 1, 'Kings Hall', 'KNG', 'male', 'Main Campus, West Quadrangle', 1, 120, true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.hostels VALUES (2, 1, 'Queen Amina Hall', 'QAM', 'female', 'Main Campus, East Quadrangle', NULL, 150, true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: invoice_items; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.invoice_items VALUES (1, 1, 1, 'Academic Tuition Base', 100000.00, true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoice_items VALUES (2, 1, 1, 'Laboratory & Workshop Levy', 15000.00, true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoice_items VALUES (3, 1, 1, 'ICT Development & Connectivity Fee', 5000.00, true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoice_items VALUES (4, 1, 2, 'Room Allocation Kings Hall - Room 204 (Bed B)', 45000.00, true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoice_items VALUES (5, 1, 3, 'Faculty of Computing Annual Levy', 5000.00, false, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoice_items VALUES (6, 1, 3, 'Computer Science Students Association (NACOS) Dues', 2500.00, false, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoice_items VALUES (7, 1, 4, 'Undergraduate Acceptance Fee', 25000.00, true, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: invoices; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.invoices VALUES (1, 1, 4, 'INV-NVU-2025-001', '2025/2026 First Semester Tuition Fee', 'tuition', '2025/2026', 'first', 120000.00, 1500.00, 121500.00, 121500.00, 'paid', '2026-07-08', true, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoices VALUES (2, 1, 4, 'INV-NVU-2025-002', 'Hostel Accommodation Fee - Kings Hall (Room 204)', 'hostel', '2025/2026', 'first', 45000.00, 1500.00, 46500.00, 46500.00, 'paid', '2026-07-08', false, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoices VALUES (3, 1, 4, 'INV-NVU-2025-003', 'Faculty & Departmental Dues', 'faculty_levy', '2025/2026', 'first', 7500.00, 1500.00, 9000.00, 0.00, 'unpaid', '2026-09-28', false, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.invoices VALUES (4, 1, 5, 'INV-NVU-2026-ACC', 'Provisional Admission Acceptance Fee', 'acceptance', '2026/2027', NULL, 25000.00, 1500.00, 26500.00, 26500.00, 'paid', '2026-09-22', false, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: jamb_records; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: library_borrow_records; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.library_borrow_records VALUES (1, 1, 1, 1, '2026-09-03', '2026-09-17', NULL, 0.00, 'borrowed', '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: library_items; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.library_items VALUES (1, 1, '978-0262046305', 'Introduction to Algorithms (4th Edition)', 'Thomas H. Cormen, Charles E. Leiserson, Ronald L. Rivest', 'textbook', 'Faculty of Computing & Information Technology', 'Department of Computer Science', '4th Edition', 2022, 5, 4, 'Floor 2, Shelf B4', false, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.library_items VALUES (2, 1, '978-1119800361', 'Operating System Concepts (10th Edition)', 'Abraham Silberschatz, Peter B. Galvin, Greg Gagne', 'textbook', 'Faculty of Computing & Information Technology', 'Department of Computer Science', '10th Edition', 2021, 4, 4, 'Floor 2, Shelf C1', false, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.library_items VALUES (3, 1, '978-0078022159', 'Database System Concepts (7th Edition)', 'Abraham Silberschatz, Henry F. Korth, S. Sudarshan', 'recommended', 'Faculty of Computing & Information Technology', 'Department of Computer Science', '7th Edition', 2020, 6, 6, 'Floor 2, Shelf D2', false, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.library_items VALUES (4, 1, '978-0134494166', 'Clean Architecture: A Craftsman''s Guide to Software Structure', 'Robert C. Martin', 'reference', 'Faculty of Computing & Information Technology', 'Department of Computer Science', '1st Edition', 2018, 3, 3, 'Floor 1, Shelf A3', false, NULL, '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.library_items VALUES (5, 1, 'ISSN-0098-5589', 'IEEE Transactions on Software Engineering 2025 Archive', 'IEEE Computer Society', 'journal', 'Faculty of Computing & Information Technology', 'Department of Computer Science', 'Vol. 51', 2025, 100, 100, NULL, true, 'https://novica.edu.ng/library/tse_2025.pdf', '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.migrations VALUES (1, '0001_01_00_000000_create_universities_table', 1);
INSERT INTO public.migrations VALUES (2, '0001_01_01_000000_create_users_table', 1);
INSERT INTO public.migrations VALUES (3, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO public.migrations VALUES (4, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO public.migrations VALUES (5, '2026_09_01_102823_create_permission_tables', 1);
INSERT INTO public.migrations VALUES (6, '2026_09_01_102901_create_personal_access_tokens_table', 1);
INSERT INTO public.migrations VALUES (7, '2026_09_01_110000_create_academic_structure_tables', 1);
INSERT INTO public.migrations VALUES (8, '2026_09_01_110001_create_staff_and_student_tables', 1);
INSERT INTO public.migrations VALUES (9, '2026_09_01_110002_create_admissions_tables', 1);
INSERT INTO public.migrations VALUES (10, '2026_09_02_000001_create_courses_and_registrations_tables', 1);
INSERT INTO public.migrations VALUES (11, '2026_09_02_000002_create_lms_tables', 1);
INSERT INTO public.migrations VALUES (12, '2026_09_02_000003_create_cbt_tables', 1);
INSERT INTO public.migrations VALUES (13, '2026_09_02_000004_create_results_and_grades_tables', 1);
INSERT INTO public.migrations VALUES (14, '2026_09_03_000001_create_finance_and_payments_tables', 2);
INSERT INTO public.migrations VALUES (15, '2026_09_03_000002_create_hostels_and_allocations_tables', 2);
INSERT INTO public.migrations VALUES (16, '2026_09_03_000003_create_approval_workflows_tables', 2);
INSERT INTO public.migrations VALUES (17, '2026_09_03_000004_create_clinic_tables', 2);
INSERT INTO public.migrations VALUES (18, '2026_09_03_000005_create_digital_id_cards_tables', 2);
INSERT INTO public.migrations VALUES (19, '2026_09_03_000006_create_library_tables', 2);
INSERT INTO public.migrations VALUES (20, '2026_09_04_000001_create_postgraduate_tables', 3);
INSERT INTO public.migrations VALUES (21, '2026_09_04_000002_create_ai_advisor_tables', 3);
INSERT INTO public.migrations VALUES (24, '2026_09_04_000003_create_career_tables', 4);
INSERT INTO public.migrations VALUES (25, '2026_09_04_000004_create_study_groups_tables', 4);


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.model_has_roles VALUES (2, 'App\Models\User', 1);
INSERT INTO public.model_has_roles VALUES (6, 'App\Models\User', 2);
INSERT INTO public.model_has_roles VALUES (4, 'App\Models\User', 3);
INSERT INTO public.model_has_roles VALUES (18, 'App\Models\User', 4);
INSERT INTO public.model_has_roles VALUES (22, 'App\Models\User', 5);
INSERT INTO public.model_has_roles VALUES (6, 'App\Models\User', 6);
INSERT INTO public.model_has_roles VALUES (18, 'App\Models\User', 7);
INSERT INTO public.model_has_roles VALUES (20, 'App\Models\User', 9);
INSERT INTO public.model_has_roles VALUES (18, 'App\Models\User', 9);


--
-- Data for Name: olevel_results; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: payments; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.payments VALUES (1, 1, 1, 4, 'NZT-PAY-20260115-0021', 121500.00, 1500.00, 120000.00, 'paystack', 'PSTK_REF_998811', 'successful', 'RCT-20260115-0021', 'ACCT_novica123456', NULL, '2026-07-08 11:56:00', '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.payments VALUES (2, 1, 2, 4, 'NZT-PAY-20260112-0087', 46500.00, 1500.00, 45000.00, 'paystack', 'PSTK_REF_774411', 'successful', 'HST-20260112-0087', 'ACCT_novica123456', NULL, '2026-07-08 11:56:00', '2026-09-08 11:56:00', '2026-09-08 11:56:00');
INSERT INTO public.payments VALUES (3, 1, 4, 5, 'NZT-PAY-20260310-0042', 26500.00, 1500.00, 25000.00, 'paystack', NULL, 'successful', 'RCT-20260310-0042', 'ACCT_novica123456', NULL, '2026-09-07 11:56:00', '2026-09-08 11:56:00', '2026-09-08 11:56:00');


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: pg_proposals; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.pg_proposals VALUES (1, 1, 4, 'Decentralized Federated Learning Architectures for Latency Optimization in Edge Networks', 'This doctoral research explores novel consensus topologies and privacy-preserving gradient aggregation algorithms for resource-constrained edge computing clusters.', 'https://novica.edu.ng/pg/proposals/NVU_PG_2024_0088_proposal.pdf', 'approved', 'Commendable theoretical formulation. Approved to proceed with experimental testbed construction.', '2026-05-08', 86.50, '2026-09-08 13:26:26', '2026-09-08 13:26:26');


--
-- Data for Name: pg_student_profiles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.pg_student_profiles VALUES (1, 1, 4, 'Ph.D.', 'Decentralized Federated Learning Architectures for Latency Optimization in Edge Networks', 1, 2, 'internal_defense', '2027-11-08', 15, 'low', '2026-09-08 13:26:26', '2026-09-08 13:26:26');


--
-- Data for Name: pg_supervision_logs; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.pg_supervision_logs VALUES (1, 1, 4, 1, '2026-08-29', 'Reviewed Chapter 4 simulation results on 16-node Kubernetes cluster. Observed 34% reduction in straggler penalty.', 'Prepare empirical latency chart comparisons for Chapter 5 by end of month.', 'confirmed', '2026-09-08 13:26:26', '2026-09-08 13:26:26');


--
-- Data for Name: pg_thesis_milestones; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.pg_thesis_milestones VALUES (1, 1, 4, 1, 'Introduction & Problem Statement', 'approved', 'https://novica.edu.ng/pg/thesis/ch_1.pdf', 'Rigorous analysis, approved without corrections.', '2026-07-08 13:26:26', '2026-08-08 13:26:26', '2026-09-08 13:26:26', '2026-09-08 13:26:26');
INSERT INTO public.pg_thesis_milestones VALUES (2, 1, 4, 2, 'Literature Review & Theoretical Framework', 'approved', 'https://novica.edu.ng/pg/thesis/ch_2.pdf', 'Rigorous analysis, approved without corrections.', '2026-07-08 13:26:26', '2026-08-08 13:26:26', '2026-09-08 13:26:26', '2026-09-08 13:26:26');
INSERT INTO public.pg_thesis_milestones VALUES (3, 1, 4, 3, 'System Architecture & Algorithmic Methodology', 'approved', 'https://novica.edu.ng/pg/thesis/ch_3.pdf', 'Rigorous analysis, approved without corrections.', '2026-07-08 13:26:26', '2026-08-08 13:26:26', '2026-09-08 13:26:26', '2026-09-08 13:26:26');
INSERT INTO public.pg_thesis_milestones VALUES (4, 1, 4, 4, 'Experimental Implementation & Edge Testbed', 'submitted', 'https://novica.edu.ng/pg/thesis/ch_4.pdf', NULL, '2026-07-08 13:26:26', NULL, '2026-09-08 13:26:26', '2026-09-08 13:26:26');
INSERT INTO public.pg_thesis_milestones VALUES (5, 1, 4, 5, 'Performance Evaluation & Latency Benchmarks', 'pending', NULL, NULL, NULL, NULL, '2026-09-08 13:26:26', '2026-09-08 13:26:26');
INSERT INTO public.pg_thesis_milestones VALUES (6, 1, 4, 6, 'Conclusion, Ethical Considerations & Future Work', 'pending', NULL, NULL, NULL, NULL, '2026-09-08 13:26:26', '2026-09-08 13:26:26');


--
-- Data for Name: post_utme_slots; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: programmes; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.programmes VALUES (1, 1, 1, 'B.Sc. Computer Science', 'B.Sc. CSC', 'B.Sc.', 4, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.programmes VALUES (2, 1, 2, 'B.Sc. Information Technology', 'B.Sc. IFT', 'B.Sc.', 4, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.programmes VALUES (3, 2, 3, 'B.Eng. Software Engineering', 'B.Eng. SWE', 'B.Eng.', 5, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.programmes VALUES (4, 1, 1, 'Ph.D. Computer Science', 'PHD-CSC', 'Ph.D.', 3, '2026-09-08 13:24:27', '2026-09-08 13:24:27');


--
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.roles VALUES (1, 'super_admin', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (2, 'super_admin', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (3, 'dean', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (4, 'dean', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (5, 'hod', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (6, 'hod', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (7, 'lecturer', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (8, 'lecturer', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (9, 'bursar', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (10, 'bursar', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (11, 'registrar', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (12, 'registrar', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (13, 'exam_officer', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (14, 'exam_officer', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (15, 'hostel_master', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (16, 'hostel_master', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (17, 'student', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (18, 'student', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (19, 'postgraduate', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (20, 'postgraduate', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (21, 'applicant', 'api', '2026-09-05 21:52:26', '2026-09-05 21:52:26');
INSERT INTO public.roles VALUES (22, 'applicant', 'web', '2026-09-05 21:52:26', '2026-09-05 21:52:26');


--
-- Data for Name: semester_results; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.semester_results VALUES (1, 1, 1, '2025/2026', 'first', '300L', 18, 18, 78.00, 4.33, 18, 18, 78.00, 4.33, 'Second Class Honours (Upper Division)', '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- Data for Name: staff; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.staff VALUES (1, 1, 2, 'STF/CIT/001', 1, 1, 'Prof.', 'Head of Department', 'Head of Department', 'AO', '#2E5FA3', '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.staff VALUES (2, 1, 3, 'STF/CIT/002', 1, 1, 'Dr.', 'Dean, Faculty of Computing & IT', 'Dean', 'CE', '#1A7A6E', '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.staff VALUES (3, 2, 6, 'STF/APX/001', 3, 3, 'Prof.', 'Head of Software Engineering', 'Head of Department', 'SA', '#059669', '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: student_certifications; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.student_certifications VALUES (1, 1, 1, 'AWS Certified Solutions Architect – Associate', 'Amazon Web Services', '2025-11-15', 'https://aws.amazon.com/verification/AWS-SAA-88231', '2026-09-08 13:31:31', '2026-09-08 13:31:31');


--
-- Data for Name: student_profiles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.student_profiles VALUES (1, 1, '2003-05-14', 'male', 'Anambra', 'Idemili North', 'Nigerian', '14 University Road, Campus Area', 'Lagos', 'Lagos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'O+', 'AA', NULL, NULL, NULL, false, NULL, '2026-09-05 21:52:29', '2026-09-05 21:52:29');
INSERT INTO public.student_profiles VALUES (2, 2, '2004-11-20', 'male', 'Oyo', 'Ibadan North', 'Nigerian', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'A+', 'AA', NULL, NULL, NULL, false, NULL, '2026-09-05 21:52:31', '2026-09-05 21:52:31');


--
-- Data for Name: student_projects; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.student_projects VALUES (1, 1, 1, 'Multi-Tenant Campus Microservices Gateway', 'High-throughput institutional microservices proxy handling 10k concurrent requests with Redis token-bucket rate limiting.', '["PHP 8.3","Laravel 11","Redis","PostgreSQL","Docker"]', 'https://github.com/chidi/campus-gateway', 'https://gateway.demo.novica.edu.ng', '2026-09-08 13:31:31', '2026-09-08 13:31:31');
INSERT INTO public.student_projects VALUES (2, 1, 1, 'Automated Clinical Triage & EHR System', 'Real-time student health registry and telemedicine triage queue built with WebSockets and DomPDF.', '["Vue 3","Laravel Sanctum","TailwindCSS","PostgreSQL"]', 'https://github.com/chidi/campus-ehr', NULL, '2026-09-08 13:31:31', '2026-09-08 13:31:31');


--
-- Data for Name: student_skills; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.student_skills VALUES (1, 1, 1, 'Laravel & PHP 8.3', 'technical', 'expert', '2026-09-08 13:31:31', '2026-09-08 13:31:31');
INSERT INTO public.student_skills VALUES (2, 1, 1, 'PostgreSQL & Query Optimization', 'technical', 'advanced', '2026-09-08 13:31:31', '2026-09-08 13:31:31');
INSERT INTO public.student_skills VALUES (3, 1, 1, 'Docker & Containerization', 'technical', 'intermediate', '2026-09-08 13:31:31', '2026-09-08 13:31:31');
INSERT INTO public.student_skills VALUES (4, 1, 1, 'RESTful API Design & TDD', 'technical', 'expert', '2026-09-08 13:31:31', '2026-09-08 13:31:31');
INSERT INTO public.student_skills VALUES (5, 1, 1, 'Agile Sprint Leadership', 'soft', 'advanced', '2026-09-08 13:31:31', '2026-09-08 13:31:31');


--
-- Data for Name: students; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.students VALUES (2, 2, 7, 'APEX/2022/SWE/001', '202299881122ZZ', 3, 3, 3, '200L', '2025/2026', 'first', 'UTME', 4.50, 'First Class Honours', 'ea498449e925baf2ce8ce9984e2b61acfe87614dde9dda8d35f2c1ef00883382', '2026-09-05 21:52:31', '2026-09-05 21:52:31');
INSERT INTO public.students VALUES (4, 1, 9, 'NVU/PG/2024/0088', 'PG2024987654', 1, 1, 4, '800L', '2025/2026', 'first', 'PG', 4.80, 'Distinction', '9d3f272dfd2977b789d7e19a6106536f611b87237836be54c08ea9777a4494f2', '2026-09-08 13:26:26', '2026-09-08 13:26:26');
INSERT INTO public.students VALUES (1, 1, 4, 'NVU/2021/CSC/001', '202140889100EF', 1, 1, 1, '300L', '2025/2026', 'first', 'UTME', 4.33, 'Second Class Honours (Upper Division)', '8c544f7797d3c0c316f0c1ffb5d48362fea61b3a446ea41e10d6d4955d81cff6', '2026-09-05 21:52:29', '2026-09-08 13:31:58');


--
-- Data for Name: study_group_members; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.study_group_members VALUES (1, 1, 1, 1, 'lead', '2026-08-25 13:31:31', '2026-09-08 13:31:31', '2026-09-08 13:31:31');


--
-- Data for Name: study_group_messages; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.study_group_messages VALUES (1, 1, 1, 1, 'Welcome colleagues! I have uploaded the greedy algorithm problem set. Review problem #4 before our Thursday session.', NULL, '2026-09-08 13:31:59', '2026-09-08 13:31:59');


--
-- Data for Name: study_groups; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.study_groups VALUES (1, 1, 1, 1, 'CSC 301 Advanced Algorithms Study Cell', 'Dedicated study syndicate tackling dynamic programming, Dijkstra graph proofs, and Big-O complexities for midterm exam prep.', 10, 'Every Tuesday & Thursday at 4:30 PM (Library Room 3B)', true, '2026-09-08 13:31:31', '2026-09-08 13:31:31');


--
-- Data for Name: universities; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.universities VALUES (1, 'Novica University', 'NVU', 'novica', 'portal.novica.edu.ng', NULL, '#2E5FA3', '#1A7A6E', 'info@novicauniversity.edu.ng', '08012345600', 'University Campus Road, Lagos', 'ACCT_novica123456', 'MERCH_novica_9988', 1500.00, true, '2026-09-05 21:52:27', '2026-09-05 21:52:27');
INSERT INTO public.universities VALUES (2, 'Apex Premier University', 'APEX', 'apex', 'portal.apex.edu.ng', NULL, '#059669', '#047857', 'admissions@apex.edu.ng', '08099881122', 'Apex Tech Valley, Abuja', 'ACCT_apex789012', 'MERCH_apex_3344', 2000.00, true, '2026-09-05 21:52:30', '2026-09-05 21:52:30');


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.users VALUES (1, NULL, 'Netzertech Platform Admin', 'admin@netzertech.com', '08000000001', 'admin', true, NULL, NULL, '$2y$12$byd8MilDgcG9oNZpjHvev.TtfhVkEiAUGlY4bikPyUQxwVdO9wUSW', NULL, '2026-09-05 21:52:27', '2026-09-05 21:52:27');
INSERT INTO public.users VALUES (2, 1, 'Prof. Adebayo Olatunji', 'adebayo.olatunji@novicauniversity.edu.ng', '08048825053', 'staff', true, NULL, NULL, '$2y$12$IEK4PNdnMsdK0nwF6KzfzevXSJCWs1CCYh2//iSLLvWdapZI89HJe', NULL, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.users VALUES (3, 1, 'Dr. Chioma Eze', 'chioma.eze@novicauniversity.edu.ng', '08020846800', 'staff', true, NULL, NULL, '$2y$12$nMVsrryo3MgSM1VEUAK3suyuKgVuqa.0VT9tSwpLLn6yiWhcP3EJ.', NULL, '2026-09-05 21:52:28', '2026-09-05 21:52:28');
INSERT INTO public.users VALUES (4, 1, 'Chidi Okonkwo', 'chidi@novicauniversity.edu.ng', '08012345678', 'student', true, NULL, NULL, '$2y$12$kwSil6juI0V3cC89ddRqO.xPFWxHOrvhpKWdpxRZC3p6s3sWiMN62', NULL, '2026-09-05 21:52:29', '2026-09-05 21:52:29');
INSERT INTO public.users VALUES (5, 1, 'Amina Yusuf', 'amina.yusuf@gmail.com', '08098765432', 'applicant', true, NULL, NULL, '$2y$12$ElxbKoc5C0rJ/FKgTDET/OVFhT2kw/4pxj8r6HRQqtWsxqIxOw44m', NULL, '2026-09-05 21:52:29', '2026-09-05 21:52:29');
INSERT INTO public.users VALUES (6, 2, 'Prof. Samuel Adeleke', 'samuel.adeleke@apex.edu.ng', '08033334444', 'staff', true, NULL, NULL, '$2y$12$9Hn/iEtngznggyUmxhCWLecw70eeQZwvAAZPlYDCrzrEMgyUuBUR6', NULL, '2026-09-05 21:52:30', '2026-09-05 21:52:30');
INSERT INTO public.users VALUES (7, 2, 'Tunde Bakare', 'tunde.bakare@apex.edu.ng', '08055667788', 'student', true, NULL, NULL, '$2y$12$AWQGcaEuhw59xybhVaPmUONE8mzhDXkl3RTNNQUNSWuy1OnRtoF7i', NULL, '2026-09-05 21:52:31', '2026-09-05 21:52:31');
INSERT INTO public.users VALUES (9, 1, 'Fatima Danladi', 'fatima.pg@novicauniversity.edu.ng', '08077665544', 'student', true, NULL, NULL, '$2y$12$6jYI/V5LZAhO7oi3sr98ae176TkkLhZV6G3Cr2YBFaDxyoHjAzFga', NULL, '2026-09-08 13:25:29', '2026-09-08 13:25:29');


--
-- Name: admissions_applications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.admissions_applications_id_seq', 1, true);


--
-- Name: ai_conversations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ai_conversations_id_seq', 1, false);


--
-- Name: ai_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ai_messages_id_seq', 1, false);


--
-- Name: approval_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.approval_requests_id_seq', 1, true);


--
-- Name: approval_steps_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.approval_steps_id_seq', 2, true);


--
-- Name: assignment_submissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.assignment_submissions_id_seq', 1, true);


--
-- Name: assignments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.assignments_id_seq', 1, true);


--
-- Name: bed_spaces_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.bed_spaces_id_seq', 8, true);


--
-- Name: career_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.career_jobs_id_seq', 2, true);


--
-- Name: cbt_exams_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cbt_exams_id_seq', 1, true);


--
-- Name: cbt_questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cbt_questions_id_seq', 3, true);


--
-- Name: cbt_student_sessions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cbt_student_sessions_id_seq', 1, false);


--
-- Name: clinic_appointments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.clinic_appointments_id_seq', 1, true);


--
-- Name: clinic_registrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.clinic_registrations_id_seq', 1, true);


--
-- Name: course_materials_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.course_materials_id_seq', 1, true);


--
-- Name: course_registration_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.course_registration_items_id_seq', 6, true);


--
-- Name: course_registrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.course_registrations_id_seq', 1, true);


--
-- Name: course_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.course_results_id_seq', 6, true);


--
-- Name: courses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.courses_id_seq', 7, true);


--
-- Name: departments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.departments_id_seq', 3, true);


--
-- Name: digital_id_cards_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.digital_id_cards_id_seq', 1, true);


--
-- Name: faculties_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.faculties_id_seq', 3, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: hostel_allocations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hostel_allocations_id_seq', 1, true);


--
-- Name: hostel_maintenance_tickets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hostel_maintenance_tickets_id_seq', 1, true);


--
-- Name: hostel_rooms_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hostel_rooms_id_seq', 2, true);


--
-- Name: hostels_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.hostels_id_seq', 2, true);


--
-- Name: invoice_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.invoice_items_id_seq', 7, true);


--
-- Name: invoices_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.invoices_id_seq', 4, true);


--
-- Name: jamb_records_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jamb_records_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: library_borrow_records_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.library_borrow_records_id_seq', 1, true);


--
-- Name: library_items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.library_items_id_seq', 5, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 25, true);


--
-- Name: olevel_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.olevel_results_id_seq', 1, false);


--
-- Name: payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.payments_id_seq', 3, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: pg_proposals_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.pg_proposals_id_seq', 1, true);


--
-- Name: pg_student_profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.pg_student_profiles_id_seq', 1, true);


--
-- Name: pg_supervision_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.pg_supervision_logs_id_seq', 1, true);


--
-- Name: pg_thesis_milestones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.pg_thesis_milestones_id_seq', 6, true);


--
-- Name: post_utme_slots_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.post_utme_slots_id_seq', 1, false);


--
-- Name: programmes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.programmes_id_seq', 4, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.roles_id_seq', 22, true);


--
-- Name: semester_results_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.semester_results_id_seq', 1, true);


--
-- Name: staff_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.staff_id_seq', 3, true);


--
-- Name: student_certifications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.student_certifications_id_seq', 1, true);


--
-- Name: student_profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.student_profiles_id_seq', 2, true);


--
-- Name: student_projects_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.student_projects_id_seq', 2, true);


--
-- Name: student_skills_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.student_skills_id_seq', 5, true);


--
-- Name: students_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.students_id_seq', 4, true);


--
-- Name: study_group_members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.study_group_members_id_seq', 1, true);


--
-- Name: study_group_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.study_group_messages_id_seq', 1, true);


--
-- Name: study_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.study_groups_id_seq', 1, true);


--
-- Name: universities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.universities_id_seq', 2, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 9, true);


--
-- Name: admissions_applications admissions_applications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admissions_applications
    ADD CONSTRAINT admissions_applications_pkey PRIMARY KEY (id);


--
-- Name: admissions_applications admissions_applications_university_id_application_no_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admissions_applications
    ADD CONSTRAINT admissions_applications_university_id_application_no_unique UNIQUE (university_id, application_no);


--
-- Name: ai_conversations ai_conversations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_conversations
    ADD CONSTRAINT ai_conversations_pkey PRIMARY KEY (id);


--
-- Name: ai_messages ai_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_messages
    ADD CONSTRAINT ai_messages_pkey PRIMARY KEY (id);


--
-- Name: approval_requests approval_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_requests
    ADD CONSTRAINT approval_requests_pkey PRIMARY KEY (id);


--
-- Name: approval_requests approval_requests_request_ref_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_requests
    ADD CONSTRAINT approval_requests_request_ref_unique UNIQUE (request_ref);


--
-- Name: approval_steps approval_steps_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_steps
    ADD CONSTRAINT approval_steps_pkey PRIMARY KEY (id);


--
-- Name: assignment_submissions assignment_submissions_assignment_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignment_submissions
    ADD CONSTRAINT assignment_submissions_assignment_id_student_id_unique UNIQUE (assignment_id, student_id);


--
-- Name: assignment_submissions assignment_submissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignment_submissions
    ADD CONSTRAINT assignment_submissions_pkey PRIMARY KEY (id);


--
-- Name: assignments assignments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_pkey PRIMARY KEY (id);


--
-- Name: bed_spaces bed_spaces_hostel_room_id_bed_label_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bed_spaces
    ADD CONSTRAINT bed_spaces_hostel_room_id_bed_label_unique UNIQUE (hostel_room_id, bed_label);


--
-- Name: bed_spaces bed_spaces_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bed_spaces
    ADD CONSTRAINT bed_spaces_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: career_jobs career_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.career_jobs
    ADD CONSTRAINT career_jobs_pkey PRIMARY KEY (id);


--
-- Name: cbt_exams cbt_exams_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_exams
    ADD CONSTRAINT cbt_exams_pkey PRIMARY KEY (id);


--
-- Name: cbt_questions cbt_questions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_questions
    ADD CONSTRAINT cbt_questions_pkey PRIMARY KEY (id);


--
-- Name: cbt_student_sessions cbt_student_sessions_cbt_exam_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_student_sessions
    ADD CONSTRAINT cbt_student_sessions_cbt_exam_id_student_id_unique UNIQUE (cbt_exam_id, student_id);


--
-- Name: cbt_student_sessions cbt_student_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_student_sessions
    ADD CONSTRAINT cbt_student_sessions_pkey PRIMARY KEY (id);


--
-- Name: clinic_appointments clinic_appointments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_appointments
    ADD CONSTRAINT clinic_appointments_pkey PRIMARY KEY (id);


--
-- Name: clinic_registrations clinic_registrations_hospital_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_registrations
    ADD CONSTRAINT clinic_registrations_hospital_number_unique UNIQUE (hospital_number);


--
-- Name: clinic_registrations clinic_registrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_registrations
    ADD CONSTRAINT clinic_registrations_pkey PRIMARY KEY (id);


--
-- Name: clinic_registrations clinic_registrations_university_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_registrations
    ADD CONSTRAINT clinic_registrations_university_id_student_id_unique UNIQUE (university_id, student_id);


--
-- Name: course_materials course_materials_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_materials
    ADD CONSTRAINT course_materials_pkey PRIMARY KEY (id);


--
-- Name: course_registration_items course_registration_items_course_registration_id_course_id_uniq; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registration_items
    ADD CONSTRAINT course_registration_items_course_registration_id_course_id_uniq UNIQUE (course_registration_id, course_id);


--
-- Name: course_registration_items course_registration_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registration_items
    ADD CONSTRAINT course_registration_items_pkey PRIMARY KEY (id);


--
-- Name: course_registrations course_registrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registrations
    ADD CONSTRAINT course_registrations_pkey PRIMARY KEY (id);


--
-- Name: course_registrations course_registrations_student_id_academic_session_semester_uniqu; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registrations
    ADD CONSTRAINT course_registrations_student_id_academic_session_semester_uniqu UNIQUE (student_id, academic_session, semester);


--
-- Name: course_results course_results_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_results
    ADD CONSTRAINT course_results_pkey PRIMARY KEY (id);


--
-- Name: course_results course_results_student_id_course_id_academic_session_semester_u; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_results
    ADD CONSTRAINT course_results_student_id_course_id_academic_session_semester_u UNIQUE (student_id, course_id, academic_session, semester);


--
-- Name: courses courses_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_pkey PRIMARY KEY (id);


--
-- Name: courses courses_university_id_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_university_id_code_unique UNIQUE (university_id, code);


--
-- Name: departments departments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_pkey PRIMARY KEY (id);


--
-- Name: departments departments_university_id_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_university_id_code_unique UNIQUE (university_id, code);


--
-- Name: digital_id_cards digital_id_cards_barcode_hash_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.digital_id_cards
    ADD CONSTRAINT digital_id_cards_barcode_hash_unique UNIQUE (barcode_hash);


--
-- Name: digital_id_cards digital_id_cards_card_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.digital_id_cards
    ADD CONSTRAINT digital_id_cards_card_number_unique UNIQUE (card_number);


--
-- Name: digital_id_cards digital_id_cards_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.digital_id_cards
    ADD CONSTRAINT digital_id_cards_pkey PRIMARY KEY (id);


--
-- Name: digital_id_cards digital_id_cards_university_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.digital_id_cards
    ADD CONSTRAINT digital_id_cards_university_id_student_id_unique UNIQUE (university_id, student_id);


--
-- Name: faculties faculties_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.faculties
    ADD CONSTRAINT faculties_pkey PRIMARY KEY (id);


--
-- Name: faculties faculties_university_id_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.faculties
    ADD CONSTRAINT faculties_university_id_code_unique UNIQUE (university_id, code);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: hostel_allocations hostel_allocations_allocation_ref_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_allocations
    ADD CONSTRAINT hostel_allocations_allocation_ref_unique UNIQUE (allocation_ref);


--
-- Name: hostel_allocations hostel_allocations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_allocations
    ADD CONSTRAINT hostel_allocations_pkey PRIMARY KEY (id);


--
-- Name: hostel_maintenance_tickets hostel_maintenance_tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_maintenance_tickets
    ADD CONSTRAINT hostel_maintenance_tickets_pkey PRIMARY KEY (id);


--
-- Name: hostel_maintenance_tickets hostel_maintenance_tickets_ticket_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_maintenance_tickets
    ADD CONSTRAINT hostel_maintenance_tickets_ticket_number_unique UNIQUE (ticket_number);


--
-- Name: hostel_rooms hostel_rooms_hostel_id_room_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_rooms
    ADD CONSTRAINT hostel_rooms_hostel_id_room_number_unique UNIQUE (hostel_id, room_number);


--
-- Name: hostel_rooms hostel_rooms_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_rooms
    ADD CONSTRAINT hostel_rooms_pkey PRIMARY KEY (id);


--
-- Name: hostels hostels_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostels
    ADD CONSTRAINT hostels_pkey PRIMARY KEY (id);


--
-- Name: hostels hostels_university_id_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostels
    ADD CONSTRAINT hostels_university_id_code_unique UNIQUE (university_id, code);


--
-- Name: invoice_items invoice_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_items
    ADD CONSTRAINT invoice_items_pkey PRIMARY KEY (id);


--
-- Name: invoices invoices_invoice_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_invoice_number_unique UNIQUE (invoice_number);


--
-- Name: invoices invoices_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_pkey PRIMARY KEY (id);


--
-- Name: jamb_records jamb_records_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jamb_records
    ADD CONSTRAINT jamb_records_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: library_borrow_records library_borrow_records_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_borrow_records
    ADD CONSTRAINT library_borrow_records_pkey PRIMARY KEY (id);


--
-- Name: library_items library_items_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_items
    ADD CONSTRAINT library_items_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: olevel_results olevel_results_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.olevel_results
    ADD CONSTRAINT olevel_results_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- Name: payments payments_receipt_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_receipt_number_unique UNIQUE (receipt_number);


--
-- Name: payments payments_transaction_reference_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_transaction_reference_unique UNIQUE (transaction_reference);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: pg_proposals pg_proposals_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_proposals
    ADD CONSTRAINT pg_proposals_pkey PRIMARY KEY (id);


--
-- Name: pg_student_profiles pg_student_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_student_profiles
    ADD CONSTRAINT pg_student_profiles_pkey PRIMARY KEY (id);


--
-- Name: pg_student_profiles pg_student_profiles_university_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_student_profiles
    ADD CONSTRAINT pg_student_profiles_university_id_student_id_unique UNIQUE (university_id, student_id);


--
-- Name: pg_supervision_logs pg_supervision_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_supervision_logs
    ADD CONSTRAINT pg_supervision_logs_pkey PRIMARY KEY (id);


--
-- Name: pg_thesis_milestones pg_thesis_milestones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_thesis_milestones
    ADD CONSTRAINT pg_thesis_milestones_pkey PRIMARY KEY (id);


--
-- Name: pg_thesis_milestones pg_thesis_milestones_student_id_chapter_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_thesis_milestones
    ADD CONSTRAINT pg_thesis_milestones_student_id_chapter_number_unique UNIQUE (student_id, chapter_number);


--
-- Name: post_utme_slots post_utme_slots_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.post_utme_slots
    ADD CONSTRAINT post_utme_slots_pkey PRIMARY KEY (id);


--
-- Name: programmes programmes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.programmes
    ADD CONSTRAINT programmes_pkey PRIMARY KEY (id);


--
-- Name: programmes programmes_university_id_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.programmes
    ADD CONSTRAINT programmes_university_id_code_unique UNIQUE (university_id, code);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: semester_results semester_results_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_results
    ADD CONSTRAINT semester_results_pkey PRIMARY KEY (id);


--
-- Name: semester_results semester_results_student_id_academic_session_semester_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_results
    ADD CONSTRAINT semester_results_student_id_academic_session_semester_unique UNIQUE (student_id, academic_session, semester);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: staff staff_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_pkey PRIMARY KEY (id);


--
-- Name: staff staff_university_id_staff_no_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_university_id_staff_no_unique UNIQUE (university_id, staff_no);


--
-- Name: student_certifications student_certifications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_certifications
    ADD CONSTRAINT student_certifications_pkey PRIMARY KEY (id);


--
-- Name: student_profiles student_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_profiles
    ADD CONSTRAINT student_profiles_pkey PRIMARY KEY (id);


--
-- Name: student_projects student_projects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_projects
    ADD CONSTRAINT student_projects_pkey PRIMARY KEY (id);


--
-- Name: student_skills student_skills_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_skills
    ADD CONSTRAINT student_skills_pkey PRIMARY KEY (id);


--
-- Name: students students_digital_id_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_digital_id_token_unique UNIQUE (digital_id_token);


--
-- Name: students students_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_pkey PRIMARY KEY (id);


--
-- Name: students students_university_id_matric_number_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_university_id_matric_number_unique UNIQUE (university_id, matric_number);


--
-- Name: study_group_members study_group_members_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_members
    ADD CONSTRAINT study_group_members_pkey PRIMARY KEY (id);


--
-- Name: study_group_members study_group_members_study_group_id_student_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_members
    ADD CONSTRAINT study_group_members_study_group_id_student_id_unique UNIQUE (study_group_id, student_id);


--
-- Name: study_group_messages study_group_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_messages
    ADD CONSTRAINT study_group_messages_pkey PRIMARY KEY (id);


--
-- Name: study_groups study_groups_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_groups
    ADD CONSTRAINT study_groups_pkey PRIMARY KEY (id);


--
-- Name: universities universities_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.universities
    ADD CONSTRAINT universities_code_unique UNIQUE (code);


--
-- Name: universities universities_custom_domain_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.universities
    ADD CONSTRAINT universities_custom_domain_unique UNIQUE (custom_domain);


--
-- Name: universities universities_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.universities
    ADD CONSTRAINT universities_pkey PRIMARY KEY (id);


--
-- Name: universities universities_subdomain_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.universities
    ADD CONSTRAINT universities_subdomain_unique UNIQUE (subdomain);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_phone_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_phone_unique UNIQUE (phone);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: ai_conversations_university_id_user_id_session_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX ai_conversations_university_id_user_id_session_id_index ON public.ai_conversations USING btree (university_id, user_id, session_id);


--
-- Name: ai_messages_university_id_conversation_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX ai_messages_university_id_conversation_id_index ON public.ai_messages USING btree (university_id, conversation_id);


--
-- Name: approval_requests_university_id_student_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX approval_requests_university_id_student_id_status_index ON public.approval_requests USING btree (university_id, student_id, status);


--
-- Name: approval_requests_university_id_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX approval_requests_university_id_type_index ON public.approval_requests USING btree (university_id, type);


--
-- Name: approval_steps_university_id_approval_request_id_level_number_i; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX approval_steps_university_id_approval_request_id_level_number_i ON public.approval_steps USING btree (university_id, approval_request_id, level_number);


--
-- Name: approval_steps_university_id_required_role_action_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX approval_steps_university_id_required_role_action_index ON public.approval_steps USING btree (university_id, required_role, action);


--
-- Name: bed_spaces_university_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bed_spaces_university_id_status_index ON public.bed_spaces USING btree (university_id, status);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: career_jobs_university_id_is_active_job_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX career_jobs_university_id_is_active_job_type_index ON public.career_jobs USING btree (university_id, is_active, job_type);


--
-- Name: clinic_appointments_university_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinic_appointments_university_id_status_index ON public.clinic_appointments USING btree (university_id, status);


--
-- Name: clinic_appointments_university_id_student_id_visit_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinic_appointments_university_id_student_id_visit_date_index ON public.clinic_appointments USING btree (university_id, student_id, visit_date);


--
-- Name: clinic_registrations_university_id_hospital_number_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX clinic_registrations_university_id_hospital_number_index ON public.clinic_registrations USING btree (university_id, hospital_number);


--
-- Name: digital_id_cards_university_id_barcode_hash_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX digital_id_cards_university_id_barcode_hash_index ON public.digital_id_cards USING btree (university_id, barcode_hash);


--
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- Name: hostel_allocations_university_id_bed_space_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX hostel_allocations_university_id_bed_space_id_index ON public.hostel_allocations USING btree (university_id, bed_space_id);


--
-- Name: hostel_allocations_university_id_student_id_academic_session_in; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX hostel_allocations_university_id_student_id_academic_session_in ON public.hostel_allocations USING btree (university_id, student_id, academic_session);


--
-- Name: hostel_maintenance_tickets_university_id_hostel_room_id_status_; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX hostel_maintenance_tickets_university_id_hostel_room_id_status_ ON public.hostel_maintenance_tickets USING btree (university_id, hostel_room_id, status);


--
-- Name: hostel_maintenance_tickets_university_id_student_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX hostel_maintenance_tickets_university_id_student_id_index ON public.hostel_maintenance_tickets USING btree (university_id, student_id);


--
-- Name: hostel_rooms_university_id_hostel_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX hostel_rooms_university_id_hostel_id_index ON public.hostel_rooms USING btree (university_id, hostel_id);


--
-- Name: hostels_university_id_gender_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX hostels_university_id_gender_index ON public.hostels USING btree (university_id, gender);


--
-- Name: invoice_items_university_id_invoice_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoice_items_university_id_invoice_id_index ON public.invoice_items USING btree (university_id, invoice_id);


--
-- Name: invoices_university_id_fee_type_academic_session_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_university_id_fee_type_academic_session_index ON public.invoices USING btree (university_id, fee_type, academic_session);


--
-- Name: invoices_university_id_user_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX invoices_university_id_user_id_status_index ON public.invoices USING btree (university_id, user_id, status);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: library_borrow_records_university_id_library_item_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX library_borrow_records_university_id_library_item_id_index ON public.library_borrow_records USING btree (university_id, library_item_id);


--
-- Name: library_borrow_records_university_id_student_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX library_borrow_records_university_id_student_id_status_index ON public.library_borrow_records USING btree (university_id, student_id, status);


--
-- Name: library_items_university_id_category_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX library_items_university_id_category_index ON public.library_items USING btree (university_id, category);


--
-- Name: library_items_university_id_title_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX library_items_university_id_title_index ON public.library_items USING btree (university_id, title);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: payments_university_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payments_university_id_status_index ON public.payments USING btree (university_id, status);


--
-- Name: payments_university_id_transaction_reference_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX payments_university_id_transaction_reference_index ON public.payments USING btree (university_id, transaction_reference);


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: pg_proposals_university_id_student_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pg_proposals_university_id_student_id_status_index ON public.pg_proposals USING btree (university_id, student_id, status);


--
-- Name: pg_student_profiles_university_id_current_stage_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pg_student_profiles_university_id_current_stage_index ON public.pg_student_profiles USING btree (university_id, current_stage);


--
-- Name: pg_student_profiles_university_id_risk_level_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pg_student_profiles_university_id_risk_level_index ON public.pg_student_profiles USING btree (university_id, risk_level);


--
-- Name: pg_supervision_logs_university_id_student_id_meeting_date_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pg_supervision_logs_university_id_student_id_meeting_date_index ON public.pg_supervision_logs USING btree (university_id, student_id, meeting_date);


--
-- Name: pg_thesis_milestones_university_id_student_id_status_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX pg_thesis_milestones_university_id_student_id_status_index ON public.pg_thesis_milestones USING btree (university_id, student_id, status);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: student_certifications_university_id_student_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX student_certifications_university_id_student_id_index ON public.student_certifications USING btree (university_id, student_id);


--
-- Name: student_projects_university_id_student_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX student_projects_university_id_student_id_index ON public.student_projects USING btree (university_id, student_id);


--
-- Name: student_skills_university_id_student_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX student_skills_university_id_student_id_index ON public.student_skills USING btree (university_id, student_id);


--
-- Name: study_group_messages_university_id_study_group_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX study_group_messages_university_id_study_group_id_index ON public.study_group_messages USING btree (university_id, study_group_id);


--
-- Name: study_groups_university_id_course_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX study_groups_university_id_course_id_index ON public.study_groups USING btree (university_id, course_id);


--
-- Name: admissions_applications admissions_applications_first_choice_programme_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admissions_applications
    ADD CONSTRAINT admissions_applications_first_choice_programme_id_foreign FOREIGN KEY (first_choice_programme_id) REFERENCES public.programmes(id) ON DELETE SET NULL;


--
-- Name: admissions_applications admissions_applications_second_choice_programme_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admissions_applications
    ADD CONSTRAINT admissions_applications_second_choice_programme_id_foreign FOREIGN KEY (second_choice_programme_id) REFERENCES public.programmes(id) ON DELETE SET NULL;


--
-- Name: admissions_applications admissions_applications_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admissions_applications
    ADD CONSTRAINT admissions_applications_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: admissions_applications admissions_applications_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admissions_applications
    ADD CONSTRAINT admissions_applications_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ai_conversations ai_conversations_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_conversations
    ADD CONSTRAINT ai_conversations_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: ai_conversations ai_conversations_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_conversations
    ADD CONSTRAINT ai_conversations_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: ai_messages ai_messages_conversation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_messages
    ADD CONSTRAINT ai_messages_conversation_id_foreign FOREIGN KEY (conversation_id) REFERENCES public.ai_conversations(id) ON DELETE CASCADE;


--
-- Name: ai_messages ai_messages_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ai_messages
    ADD CONSTRAINT ai_messages_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: approval_requests approval_requests_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_requests
    ADD CONSTRAINT approval_requests_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: approval_requests approval_requests_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_requests
    ADD CONSTRAINT approval_requests_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: approval_steps approval_steps_acted_by_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_steps
    ADD CONSTRAINT approval_steps_acted_by_user_id_foreign FOREIGN KEY (acted_by_user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: approval_steps approval_steps_approval_request_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_steps
    ADD CONSTRAINT approval_steps_approval_request_id_foreign FOREIGN KEY (approval_request_id) REFERENCES public.approval_requests(id) ON DELETE CASCADE;


--
-- Name: approval_steps approval_steps_assigned_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_steps
    ADD CONSTRAINT approval_steps_assigned_staff_id_foreign FOREIGN KEY (assigned_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: approval_steps approval_steps_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.approval_steps
    ADD CONSTRAINT approval_steps_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: assignment_submissions assignment_submissions_assignment_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignment_submissions
    ADD CONSTRAINT assignment_submissions_assignment_id_foreign FOREIGN KEY (assignment_id) REFERENCES public.assignments(id) ON DELETE CASCADE;


--
-- Name: assignment_submissions assignment_submissions_graded_by_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignment_submissions
    ADD CONSTRAINT assignment_submissions_graded_by_staff_id_foreign FOREIGN KEY (graded_by_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: assignment_submissions assignment_submissions_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignment_submissions
    ADD CONSTRAINT assignment_submissions_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: assignments assignments_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: assignments assignments_creator_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_creator_staff_id_foreign FOREIGN KEY (creator_staff_id) REFERENCES public.staff(id) ON DELETE CASCADE;


--
-- Name: assignments assignments_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.assignments
    ADD CONSTRAINT assignments_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: bed_spaces bed_spaces_hostel_room_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bed_spaces
    ADD CONSTRAINT bed_spaces_hostel_room_id_foreign FOREIGN KEY (hostel_room_id) REFERENCES public.hostel_rooms(id) ON DELETE CASCADE;


--
-- Name: bed_spaces bed_spaces_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bed_spaces
    ADD CONSTRAINT bed_spaces_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: career_jobs career_jobs_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.career_jobs
    ADD CONSTRAINT career_jobs_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: cbt_exams cbt_exams_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_exams
    ADD CONSTRAINT cbt_exams_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: cbt_exams cbt_exams_created_by_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_exams
    ADD CONSTRAINT cbt_exams_created_by_staff_id_foreign FOREIGN KEY (created_by_staff_id) REFERENCES public.staff(id) ON DELETE CASCADE;


--
-- Name: cbt_exams cbt_exams_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_exams
    ADD CONSTRAINT cbt_exams_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: cbt_questions cbt_questions_cbt_exam_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_questions
    ADD CONSTRAINT cbt_questions_cbt_exam_id_foreign FOREIGN KEY (cbt_exam_id) REFERENCES public.cbt_exams(id) ON DELETE CASCADE;


--
-- Name: cbt_student_sessions cbt_student_sessions_cbt_exam_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_student_sessions
    ADD CONSTRAINT cbt_student_sessions_cbt_exam_id_foreign FOREIGN KEY (cbt_exam_id) REFERENCES public.cbt_exams(id) ON DELETE CASCADE;


--
-- Name: cbt_student_sessions cbt_student_sessions_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_student_sessions
    ADD CONSTRAINT cbt_student_sessions_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: cbt_student_sessions cbt_student_sessions_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cbt_student_sessions
    ADD CONSTRAINT cbt_student_sessions_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: clinic_appointments clinic_appointments_doctor_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_appointments
    ADD CONSTRAINT clinic_appointments_doctor_staff_id_foreign FOREIGN KEY (doctor_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: clinic_appointments clinic_appointments_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_appointments
    ADD CONSTRAINT clinic_appointments_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: clinic_appointments clinic_appointments_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_appointments
    ADD CONSTRAINT clinic_appointments_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: clinic_registrations clinic_registrations_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_registrations
    ADD CONSTRAINT clinic_registrations_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: clinic_registrations clinic_registrations_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clinic_registrations
    ADD CONSTRAINT clinic_registrations_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: course_materials course_materials_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_materials
    ADD CONSTRAINT course_materials_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: course_materials course_materials_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_materials
    ADD CONSTRAINT course_materials_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: course_materials course_materials_uploader_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_materials
    ADD CONSTRAINT course_materials_uploader_staff_id_foreign FOREIGN KEY (uploader_staff_id) REFERENCES public.staff(id) ON DELETE CASCADE;


--
-- Name: course_registration_items course_registration_items_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registration_items
    ADD CONSTRAINT course_registration_items_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: course_registration_items course_registration_items_course_registration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registration_items
    ADD CONSTRAINT course_registration_items_course_registration_id_foreign FOREIGN KEY (course_registration_id) REFERENCES public.course_registrations(id) ON DELETE CASCADE;


--
-- Name: course_registrations course_registrations_approved_by_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registrations
    ADD CONSTRAINT course_registrations_approved_by_staff_id_foreign FOREIGN KEY (approved_by_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: course_registrations course_registrations_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registrations
    ADD CONSTRAINT course_registrations_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: course_registrations course_registrations_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_registrations
    ADD CONSTRAINT course_registrations_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: course_results course_results_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_results
    ADD CONSTRAINT course_results_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE CASCADE;


--
-- Name: course_results course_results_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_results
    ADD CONSTRAINT course_results_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: course_results course_results_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_results
    ADD CONSTRAINT course_results_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: course_results course_results_uploaded_by_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.course_results
    ADD CONSTRAINT course_results_uploaded_by_staff_id_foreign FOREIGN KEY (uploaded_by_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: courses courses_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: courses courses_prerequisite_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_prerequisite_course_id_foreign FOREIGN KEY (prerequisite_course_id) REFERENCES public.courses(id) ON DELETE SET NULL;


--
-- Name: courses courses_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.courses
    ADD CONSTRAINT courses_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: departments departments_faculty_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_faculty_id_foreign FOREIGN KEY (faculty_id) REFERENCES public.faculties(id) ON DELETE CASCADE;


--
-- Name: departments departments_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.departments
    ADD CONSTRAINT departments_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: digital_id_cards digital_id_cards_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.digital_id_cards
    ADD CONSTRAINT digital_id_cards_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: digital_id_cards digital_id_cards_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.digital_id_cards
    ADD CONSTRAINT digital_id_cards_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: faculties faculties_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.faculties
    ADD CONSTRAINT faculties_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: hostel_allocations hostel_allocations_bed_space_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_allocations
    ADD CONSTRAINT hostel_allocations_bed_space_id_foreign FOREIGN KEY (bed_space_id) REFERENCES public.bed_spaces(id) ON DELETE CASCADE;


--
-- Name: hostel_allocations hostel_allocations_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_allocations
    ADD CONSTRAINT hostel_allocations_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: hostel_allocations hostel_allocations_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_allocations
    ADD CONSTRAINT hostel_allocations_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: hostel_maintenance_tickets hostel_maintenance_tickets_hostel_room_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_maintenance_tickets
    ADD CONSTRAINT hostel_maintenance_tickets_hostel_room_id_foreign FOREIGN KEY (hostel_room_id) REFERENCES public.hostel_rooms(id) ON DELETE CASCADE;


--
-- Name: hostel_maintenance_tickets hostel_maintenance_tickets_resolved_by_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_maintenance_tickets
    ADD CONSTRAINT hostel_maintenance_tickets_resolved_by_staff_id_foreign FOREIGN KEY (resolved_by_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: hostel_maintenance_tickets hostel_maintenance_tickets_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_maintenance_tickets
    ADD CONSTRAINT hostel_maintenance_tickets_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: hostel_maintenance_tickets hostel_maintenance_tickets_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_maintenance_tickets
    ADD CONSTRAINT hostel_maintenance_tickets_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: hostel_rooms hostel_rooms_hostel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_rooms
    ADD CONSTRAINT hostel_rooms_hostel_id_foreign FOREIGN KEY (hostel_id) REFERENCES public.hostels(id) ON DELETE CASCADE;


--
-- Name: hostel_rooms hostel_rooms_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostel_rooms
    ADD CONSTRAINT hostel_rooms_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: hostels hostels_master_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostels
    ADD CONSTRAINT hostels_master_staff_id_foreign FOREIGN KEY (master_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: hostels hostels_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.hostels
    ADD CONSTRAINT hostels_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: invoice_items invoice_items_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_items
    ADD CONSTRAINT invoice_items_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.invoices(id) ON DELETE CASCADE;


--
-- Name: invoice_items invoice_items_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoice_items
    ADD CONSTRAINT invoice_items_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: invoices invoices_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.invoices
    ADD CONSTRAINT invoices_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: jamb_records jamb_records_application_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jamb_records
    ADD CONSTRAINT jamb_records_application_id_foreign FOREIGN KEY (application_id) REFERENCES public.admissions_applications(id) ON DELETE CASCADE;


--
-- Name: library_borrow_records library_borrow_records_library_item_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_borrow_records
    ADD CONSTRAINT library_borrow_records_library_item_id_foreign FOREIGN KEY (library_item_id) REFERENCES public.library_items(id) ON DELETE CASCADE;


--
-- Name: library_borrow_records library_borrow_records_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_borrow_records
    ADD CONSTRAINT library_borrow_records_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: library_borrow_records library_borrow_records_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_borrow_records
    ADD CONSTRAINT library_borrow_records_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: library_items library_items_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.library_items
    ADD CONSTRAINT library_items_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: olevel_results olevel_results_application_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.olevel_results
    ADD CONSTRAINT olevel_results_application_id_foreign FOREIGN KEY (application_id) REFERENCES public.admissions_applications(id) ON DELETE CASCADE;


--
-- Name: payments payments_invoice_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES public.invoices(id) ON DELETE CASCADE;


--
-- Name: payments payments_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: payments payments_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: pg_proposals pg_proposals_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_proposals
    ADD CONSTRAINT pg_proposals_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: pg_proposals pg_proposals_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_proposals
    ADD CONSTRAINT pg_proposals_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: pg_student_profiles pg_student_profiles_co_supervisor_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_student_profiles
    ADD CONSTRAINT pg_student_profiles_co_supervisor_staff_id_foreign FOREIGN KEY (co_supervisor_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: pg_student_profiles pg_student_profiles_primary_supervisor_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_student_profiles
    ADD CONSTRAINT pg_student_profiles_primary_supervisor_staff_id_foreign FOREIGN KEY (primary_supervisor_staff_id) REFERENCES public.staff(id) ON DELETE SET NULL;


--
-- Name: pg_student_profiles pg_student_profiles_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_student_profiles
    ADD CONSTRAINT pg_student_profiles_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: pg_student_profiles pg_student_profiles_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_student_profiles
    ADD CONSTRAINT pg_student_profiles_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: pg_supervision_logs pg_supervision_logs_staff_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_supervision_logs
    ADD CONSTRAINT pg_supervision_logs_staff_id_foreign FOREIGN KEY (staff_id) REFERENCES public.staff(id) ON DELETE CASCADE;


--
-- Name: pg_supervision_logs pg_supervision_logs_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_supervision_logs
    ADD CONSTRAINT pg_supervision_logs_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: pg_supervision_logs pg_supervision_logs_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_supervision_logs
    ADD CONSTRAINT pg_supervision_logs_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: pg_thesis_milestones pg_thesis_milestones_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_thesis_milestones
    ADD CONSTRAINT pg_thesis_milestones_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: pg_thesis_milestones pg_thesis_milestones_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pg_thesis_milestones
    ADD CONSTRAINT pg_thesis_milestones_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: post_utme_slots post_utme_slots_application_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.post_utme_slots
    ADD CONSTRAINT post_utme_slots_application_id_foreign FOREIGN KEY (application_id) REFERENCES public.admissions_applications(id) ON DELETE CASCADE;


--
-- Name: programmes programmes_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.programmes
    ADD CONSTRAINT programmes_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: programmes programmes_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.programmes
    ADD CONSTRAINT programmes_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: semester_results semester_results_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_results
    ADD CONSTRAINT semester_results_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: semester_results semester_results_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.semester_results
    ADD CONSTRAINT semester_results_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: staff staff_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE SET NULL;


--
-- Name: staff staff_faculty_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_faculty_id_foreign FOREIGN KEY (faculty_id) REFERENCES public.faculties(id) ON DELETE SET NULL;


--
-- Name: staff staff_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: staff staff_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.staff
    ADD CONSTRAINT staff_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: student_certifications student_certifications_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_certifications
    ADD CONSTRAINT student_certifications_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: student_certifications student_certifications_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_certifications
    ADD CONSTRAINT student_certifications_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: student_profiles student_profiles_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_profiles
    ADD CONSTRAINT student_profiles_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: student_projects student_projects_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_projects
    ADD CONSTRAINT student_projects_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: student_projects student_projects_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_projects
    ADD CONSTRAINT student_projects_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: student_skills student_skills_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_skills
    ADD CONSTRAINT student_skills_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: student_skills student_skills_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.student_skills
    ADD CONSTRAINT student_skills_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: students students_department_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_department_id_foreign FOREIGN KEY (department_id) REFERENCES public.departments(id) ON DELETE CASCADE;


--
-- Name: students students_faculty_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_faculty_id_foreign FOREIGN KEY (faculty_id) REFERENCES public.faculties(id) ON DELETE CASCADE;


--
-- Name: students students_programme_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_programme_id_foreign FOREIGN KEY (programme_id) REFERENCES public.programmes(id) ON DELETE CASCADE;


--
-- Name: students students_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: students students_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.students
    ADD CONSTRAINT students_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: study_group_members study_group_members_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_members
    ADD CONSTRAINT study_group_members_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: study_group_members study_group_members_study_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_members
    ADD CONSTRAINT study_group_members_study_group_id_foreign FOREIGN KEY (study_group_id) REFERENCES public.study_groups(id) ON DELETE CASCADE;


--
-- Name: study_group_members study_group_members_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_members
    ADD CONSTRAINT study_group_members_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: study_group_messages study_group_messages_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_messages
    ADD CONSTRAINT study_group_messages_student_id_foreign FOREIGN KEY (student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: study_group_messages study_group_messages_study_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_messages
    ADD CONSTRAINT study_group_messages_study_group_id_foreign FOREIGN KEY (study_group_id) REFERENCES public.study_groups(id) ON DELETE CASCADE;


--
-- Name: study_group_messages study_group_messages_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_group_messages
    ADD CONSTRAINT study_group_messages_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: study_groups study_groups_course_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_groups
    ADD CONSTRAINT study_groups_course_id_foreign FOREIGN KEY (course_id) REFERENCES public.courses(id) ON DELETE SET NULL;


--
-- Name: study_groups study_groups_creator_student_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_groups
    ADD CONSTRAINT study_groups_creator_student_id_foreign FOREIGN KEY (creator_student_id) REFERENCES public.students(id) ON DELETE CASCADE;


--
-- Name: study_groups study_groups_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.study_groups
    ADD CONSTRAINT study_groups_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE CASCADE;


--
-- Name: users users_university_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_university_id_foreign FOREIGN KEY (university_id) REFERENCES public.universities(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict f8B6YZc1Xj6RESZ7NqecJQbLsaX3cbhHekEAuyMxrJ07bn1eyRHsslQFtfBgKGR

