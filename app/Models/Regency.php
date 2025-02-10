<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Regency extends Model
{
    use HasFactory;

    protected $table = 'wil_regencies';
    protected $primaryKey = 'kode';
    protected $fillable = ['kode', 'prov_kode', 'nama'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'prov_kode', 'kode');
    }

    public static function getAvailableWarriorInRegency(array $provinces)
    {
        return self::whereIn('prov_kode', $provinces)->pluck('nama', 'kode');
    }

    public static function accountCount($kode): int
    {
        return User::where('regency_kode', $kode)->whereHas('roles', function($query){
            $query->where('name', UserRole::PASUKAN->value);
        })->count();
    }

    public static function getRegencyName($specific_regency)
    {
        return self::whereIn('kode', $specific_regency)->pluck('nama')->toArray();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'regency_kode', 'kode');
    }
}
