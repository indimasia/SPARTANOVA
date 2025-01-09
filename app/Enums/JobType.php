<?php

namespace App\Enums;

use App\Models\PackageRate;

enum JobType: string
{
    case KOMENTAR = 'Komentar';
    case VIEW = 'View';
    case POSTING = 'Posting';
    case SHARE_RETWEET = 'Share/retweet';
    case RATING_REVIEW = 'Rating & Review';
    case DOWNLOAD_RATING_REVIEW = 'Download, Rating, Review';
    case LIKE_POLLING_VOTE = 'Like/Polling/Vote';
    case SURVEI = 'Survei';
    case SUBSCRIBE_FOLLOW = 'Subscribe/Follow';
    case FOLLOW_MARKETPLACE = 'Follow Marketplace';

    public static function options()
    {
        return collect(self::cases())->pluck('value', 'value');
    }

    public static function optionsWithout(): array
    {
        return collect(self::cases())
            ->whereNotIn('value', PackageRate::pluck('type')->toArray())
            ->pluck('value', 'value')
            ->toArray();
    }
}

