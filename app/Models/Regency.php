<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    protected $table = 'wil_regencies';
    protected $primaryKey = 'kode';

    public function province()
    {
        return $this->belongsTo(Province::class, 'prov_kode', 'kode');
    }
}
