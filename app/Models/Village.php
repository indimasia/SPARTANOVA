<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
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

    public static function getAvailableWarriorInVillage($district_kode)
    {
        return self::where('district_kode', $district_kode)->pluck('nama', 'kode')->mapWithKeys(function ($village, $kode) {
            return [
                $kode => $village . ' - ' . self::accountCount($kode) . ' Pasukan'
            ];
           });
    }

    public static function accountCount($kode): int
    {
        return User::where('village_kode', $kode)->whereHas('roles', function($query){
            $query->where('name', UserRole::PASUKAN->value);
        })->count();
    }

    public static function getVillageName($specific_village)
    {
        return self::whereIn('kode', $specific_village)->pluck('nama')->toArray();
    }
}
