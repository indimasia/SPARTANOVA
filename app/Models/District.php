<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;
    protected $table = 'wil_districts';
    protected $primaryKey = 'kode';
    protected $fillable = ['kode', 'regency_kode', 'nama'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'district_kode', 'kode');
    }

    function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_kode', 'kode');

    }
    function province()
    {
        return $this->belongsTo(Province::class, 'prov_kode', 'kode');

    }

    public static function getAvailableWarriorInDistrict(array $regencies)
    {
        return self::whereIn('regency_kode', $regencies)->pluck('nama', 'kode');
    }

    public static function accountCount($kode): int
    {
        return User::where('district_kode', $kode)->whereHas('roles', function($query){
            $query->where('name', UserRole::PASUKAN->value);
        })->count();
    }

    public static function getDistrictName($specific_district)
    {
        return self::whereIn('kode', $specific_district)->pluck('nama')->toArray();
    }
}
