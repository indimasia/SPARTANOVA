<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    // protected $connection = 'andi_bebas';
    // protected $table = 'rp_vvillages';
    protected $table = 'wil_villages';
    protected $primaryKey = 'kel_id';

    public function district()
    {
        return $this->belongsTo(District::class, 'district_kode', 'kode');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_kode', 'kode');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'prov_kode', 'kode');
    }
}
