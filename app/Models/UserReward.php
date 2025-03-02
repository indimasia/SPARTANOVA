<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class UserReward extends Model
{
    protected $fillable = ['status', 'user_id', 'reward_id'];


    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reward(){
        return $this->belongsTo(Reward::class, 'reward_id', 'id');
    }
}
