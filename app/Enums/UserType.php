<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case STAFF = 'staff';
    case STUDENT = 'student';
    case POSTGRADUATE = 'postgraduate';
    case APPLICANT = 'applicant';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'System Administrator',
            self::STAFF => 'Academic / Admin Staff',
            self::STUDENT => 'Undergraduate Student',
            self::POSTGRADUATE => 'Postgraduate Student',
            self::APPLICANT => 'Prospective Applicant',
        };
    }
}
