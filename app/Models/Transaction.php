<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'type', 'amount', 'bank_account', 'transfer_proof', 'status', 'in_the_name_of', 'no_bank_account', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
