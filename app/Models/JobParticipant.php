<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class JobParticipant extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'user_id', 'status', 'reward', 'attachment'];

    public function job()
    {
        return $this->belongsTo(JobCampaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
