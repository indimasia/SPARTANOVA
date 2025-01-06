<?php

namespace App\Models;

use App\Enums\UserRole;
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

    public static function getAvailableWarriorInRegency($prov_kode)
    {
        return self::where('prov_kode', $prov_kode)->pluck('nama', 'kode')->mapWithKeys(function ($regency, $kode) {
            return [
                $kode => $regency . ' - ' . self::accountCount($kode) . ' Pasukan'
            ];
           });
    }

    public static function accountCount($kode): int
    {
        return User::where('regency_kode', $kode)->whereHas('roles', function($query){
            $query->where('name', UserRole::PEJUANG->value);
        })->count();
    }
}
