<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobDetail extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'image', 'description', 'url_link'];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
