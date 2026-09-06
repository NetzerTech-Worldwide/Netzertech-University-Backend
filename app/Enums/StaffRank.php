<?php

namespace App\Enums;

enum StaffRank: string
{
    case LECTURER = 'Lecturer';
    case HOD = 'Head of Department';
    case DEAN = 'Dean';
    case REGISTRAR = 'Registrar';
    case BURSAR = 'Bursar';
    case VC = 'Vice Chancellor';
    case EXAM_OFFICER = 'Examinations Officer';
    case HOSTEL_MASTER = 'Hostel Master';
    case MEDICAL_OFFICER = 'Medical Officer';
    case ADMIN_STAFF = 'Administrative Staff';
}
