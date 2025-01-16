<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case ACTIVE = 'aktif';
    case SUSPENDED = 'suspended';
    case PENDING = 'pending';
    case REJECTED = 'rejected';

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }
}
