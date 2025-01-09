<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\JobType;
use App\Enums\PlatformEnum;

class JobCampaign extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'platform', 'quota', 'reward', 'status', 'due_date', 'is_multiple', 'start_date', 'end_date', 'instructions', 'created_by'];

    protected $appends = ['participant_count'];
    
    public function getParticipantCountAttribute(): int
    {
        return $this->participants()->count();
    }
    protected $casts = [
        'type' => JobType::class,
        'platform' => PlatformEnum::class,
    ];

    public function jobDetail(): HasOne
    {
        return $this->hasOne(JobDetail::class, 'job_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(JobParticipant::class,'job_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    
}
