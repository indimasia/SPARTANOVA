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
        'probability',
        'is_available',
    ];


    public function userRewaards(){
        $this->hasMany(UserReward::class, 'reward_id', 'id');
    }

}
