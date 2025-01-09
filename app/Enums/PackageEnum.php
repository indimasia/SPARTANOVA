<?php

namespace App\Enums;

enum PackageEnum: int
{
    case FIFTY = 50;
    case HUNDRED = 100;
    case FIVE_HUNDRED = 500;
    case THOUSAND = 1000;
    case FIVE_THOUSAND = 5000;
    case TEN_THOUSAND = 10000;
    case LAINNYA = 0;
    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }

}
