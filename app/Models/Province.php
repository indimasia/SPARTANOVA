<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $table = 'wil_provinces';
    protected $primaryKey = 'kode';



    public static function getAvailableWarriorInProvince()
    {
       return self::pluck('nama', 'kode')->mapWithKeys(function ($province, $kode) {
        return [
            $kode => $province . ' - ' . self::accountCount($kode) . ' Pasukan'
        ];
       });
    }


    public static function accountCount($kode): int
    {
        return User::where('province_kode', $kode)->whereHas('roles', function($query){
            $query->where('name', UserRole::PASUKAN->value);
        })->count();
    }

    public static function getProvinceName($specific_province)
    {
        return self::whereIn('kode', $specific_province)->pluck('nama')->toArray();
    }

}
