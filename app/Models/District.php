<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'wil_districts';
    protected $primaryKey = 'kode';

    function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_kode', 'kode');

    }
    function province()
    {
        return $this->belongsTo(Province::class, 'prov_kode', 'kode');

    }
}
