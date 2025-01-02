<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobCampaign extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'platform', 'quota', 'reward', 'status', 'due_date', 'is_multiple'];
}
