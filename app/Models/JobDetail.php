<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Observers\JobDetailObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(JobDetailObserver::class)]
class JobDetail extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'image', 'description', 'url_link', 'specific_gender', 'specific_generation', 'specific_interest', 'specific_province', 'specific_regency', 'specific_district', 'specific_village'];
    protected $casts = [
        'specific_interest'=>'array',
        'specific_province'=>'array',
        'specific_regency'=>'array',
        'specific_district'=>'array',
        'specific_village'=>'array',
        'specific_generation'=>'array'
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobCampaign::class,'job_id');
    }
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class,'specific_province');
    }
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class,'specific_regency');
    }
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class,'specific_district');
    }
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class,'specific_village');
    }
}
