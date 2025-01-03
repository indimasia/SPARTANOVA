<?php

namespace App\Enums;

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
}
