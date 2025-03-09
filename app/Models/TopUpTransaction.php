<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopUpTransaction extends Model
{
    use HasFactory;

    protected $table = 'top_up_transactions';
    protected $fillable = ['nama_bank', 'no_rekening', 'nama_pemilik'];
}
