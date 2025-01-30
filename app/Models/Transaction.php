<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'amount', 'bank_account', 'transfer_proof', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
