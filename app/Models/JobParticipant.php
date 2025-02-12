<?php

namespace App\Models;

use App\Observers\JobParticipantObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(JobParticipantObserver::class)]
class JobParticipant extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'user_id', 'status', 'reward', 'attachment'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobCampaign::class,'job_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public static function getTotalJobsByUser($userId)
    {
        return self::where('user_id', $userId)->count();
    }

    public static function getPendingJobsByUser($userId)
    {
        return self::where('user_id', $userId)
                    ->where('status', 'reported')
                    ->count();
    }

    public static function getApprovedJobsByUser($userId)
    {
        return self::where('user_id', $userId)
                    ->where('status', 'approved')
                    ->count();
    }
}
