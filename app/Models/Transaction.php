<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id','top_up_transactions_id', 'type', 'amount', 'bank_account', 'transfer_proof', 'status', 'in_the_name_of', 'no_bank_account', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topUpTransaction()
{
    return $this->belongsTo(TopUpTransaction::class, 'top_up_transactions_id');
}

    public function getPoinDitarikAttribute($value)
    {
        $conversionRate = ConversionRate::value('conversion_rate') ?? 1; // Pastikan nilai tidak null
        return $value ? round($value / $conversionRate) . ' Poin' : '-';
    }
}
