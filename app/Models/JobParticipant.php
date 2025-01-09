<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
