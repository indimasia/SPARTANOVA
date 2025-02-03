<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversionRate extends Model
{
    protected $fillable = ['rupiah_amount', 'points_amount', 'conversion_rate'];

    protected static function booted()
    {
        static::saving(function ($conversionRate) {
            if ($conversionRate->rupiah_amount && $conversionRate->points_amount) {
                $conversionRate->conversion_rate = $conversionRate->rupiah_amount / $conversionRate->points_amount;
            }
        });
    }
    
}
