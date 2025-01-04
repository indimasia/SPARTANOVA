<?php

namespace App\Models;

use App\Enums\JobType;
use App\Enums\PackageEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRate extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'price'];
    // protected $casts = [
    //     'type' => JobType::class,
    // ];

    public function scopeWithoutType($query, $type)
    {
        return $query->whereNotIn('type', $type);
    }
    public static function packageList($type = null)
    {
        $packageList = PackageEnum::options();
        return $packageList->mapWithKeys(function ($package) use ($type) {
            if ($package !== PackageEnum::LAINNYA->value) {
                $price = $type ? self::where('type', $type)->pluck('price')->first() : 0;
                $rate = $package * $price;
                $packageName = $package . ' Pasukan - Rp. ' . number_format($rate, 0, ',', '.');
            }
            else{
                $packageName = '>10.000 Pasukan';
            }

            return [$package => $packageName];
        });

    }
}
