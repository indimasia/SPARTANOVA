<?php

namespace App\Enums;

enum GenEnum: string
{
    case BABY_BOOMERS = 'Baby Boomers';
    case GEN_X = 'Gen X';
    case GEN_Y = 'Gen Y';
    case GEN_Z = 'Gen Z';
    case GEN_ALPHA = 'Gen Alpha';

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }
    // Add other generations as needed
}
