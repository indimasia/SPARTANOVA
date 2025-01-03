<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case PENGIKLAN = 'pengiklan';
    case PEJUANG = 'pejuang';
}
