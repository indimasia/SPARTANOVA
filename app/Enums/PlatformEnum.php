<?php

namespace App\Enums;

enum PlatformEnum: string
{
    case FACEBOOK = 'Facebook';
    case INSTAGRAM = 'Instagram';
    case TWITTER = 'Twitter';
    case LINKEDIN = 'LinkedIn';
    case TIKTOK = 'TikTok';
    case YOUTUBE = 'Youtube';
    // Add other platforms as needed
    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }

} 
