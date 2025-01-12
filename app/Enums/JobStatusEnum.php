<?php

namespace App\Enums;

enum JobStatusEnum: string
{
    case APPLIED = 'Applied';
    case IN_REVIEW = 'In Review';
    case SUBMITTED = 'Submitted';
    case REJECTED = 'Rejected';
    case APPROVED = 'Approved';
    case CANCELLED = 'Cancelled';
    case REPORTED = 'Reported'; 

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }
}
