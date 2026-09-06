<?php

namespace App\Enums;

enum AdmissionStatus: string
{
    case NONE = 'none';
    case DRAFT = 'draft';
    case UNDER_REVIEW = 'under_review';
    case INVITED = 'invited';
    case ADMITTED = 'admitted';
    case NOT_ADMITTED = 'not_admitted';
    case SUPPLEMENTARY = 'supplementary';

    public function label(): string
    {
        return match ($this) {
            self::NONE => 'No Application',
            self::DRAFT => 'Application Draft',
            self::UNDER_REVIEW => 'Under Review',
            self::INVITED => 'Invited for Screening',
            self::ADMITTED => 'Provisional Admission Offered',
            self::NOT_ADMITTED => 'Not Admitted',
            self::SUPPLEMENTARY => 'Supplementary List',
        };
    }
}
