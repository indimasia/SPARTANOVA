<?php

namespace App\Models;

use App\Enums\UserRole;
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

    public static function getAvailableWarriorInDistrict($regency_kode)
    {
        return self::where('regency_kode', $regency_kode)->pluck('nama', 'kode')->mapWithKeys(function ($district, $kode) {
            return [
                $kode => $district . ' - ' . self::accountCount($kode) . ' Pasukan'
            ];
           });
    }

    public static function accountCount($kode): int
    {
        return User::where('district_kode', $kode)->whereHas('roles', function($query){
            $query->where('name', UserRole::PEJUANG->value);
        })->count();
    }
}
