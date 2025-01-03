<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\JobType;
use App\Enums\PlatformEnum;

class JobCampaign extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'platform', 'quota', 'reward', 'status', 'due_date', 'is_multiple', 'start_date', 'end_date', 'instructions'];

    protected $casts = [
        'type' => JobType::class,
        'platform' => PlatformEnum::class,
    ];

    public function jobDetail(): HasOne
    {
        return $this->hasOne(JobDetail::class, 'job_id');
    }
}
