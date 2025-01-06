<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PENGIKLAN = 'pengiklan';
    case PASUKAN = 'pasukan';

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }
}
