<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Village extends Model
{
    use HasFactory;
    protected $table = 'wil_villages';
    protected $primaryKey = 'kode';
    protected $fillable = ['kode', 'district_kode', 'nama'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'village_kode', 'kode');
    }

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

    public static function getAvailableWarriorInVillage(array $districts)
    {
        return self::whereIn('district_kode', $districts)->pluck('nama', 'kode');
    }

    public static function accountCount($kode): int
    {
        return User::where('village_kode', $kode)->whereHas('roles', function($query){
            $query->where('name', UserRole::PASUKAN->value);
        })->count();
    }

    public static function getVillageName($specific_village)
    {
        if (empty($specific_village)) {
            return null;
        }
        return self::whereIn('kode', $specific_village)->pluck('nama')->toArray();
    }
}
