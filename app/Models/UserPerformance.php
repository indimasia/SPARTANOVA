<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class UserPerformance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'job_completed', 'total_reward'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
