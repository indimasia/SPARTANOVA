<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SosialMediaAccount extends Model
{
    use HasFactory;
    protected $table = 'sosial_media_accounts';
    protected $fillable = ['user_id', 'sosial_media', 'account'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
