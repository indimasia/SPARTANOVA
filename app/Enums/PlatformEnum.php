<?php

namespace App\Enums;

enum PlatformEnum: string
{
    case FACEBOOK = 'Facebook';
    case INSTAGRAM = 'Instagram';
    case TWITTER = 'Twitter';
    case GOOGLE = 'Google';
    case TIKTOK = 'TikTok';
    case YOUTUBE = 'Youtube';
    case WHATSAPP = 'WhatsApp';
    // Add other platforms as needed
    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }

} 
