<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PENGIKLAN = 'pengiklan';
    case PEJUANG = 'pejuang';

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }
}
