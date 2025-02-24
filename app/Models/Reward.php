<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'quantity',
        'rarity',
        'probability',
        'status',
    ];

    public function getWeightAttribute()
    {
        return match ($this->rarity) {
            'common' => 60,
            'rare' => 25,
            'epic' => 10,
            'legendary' => 5,
            default => 0,
        };
    }

}
