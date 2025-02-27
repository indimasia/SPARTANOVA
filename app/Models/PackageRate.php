<?php

namespace App\Models;

use App\Enums\JobType;
use App\Enums\PackageEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRate extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'price', 'reward'];
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
                $rupiah = ConversionRate::pointToRupiah($rate);
                $packageName = $package . ' Pasukan - Poin ' . $rate . ' - Rp. ' . number_format($rupiah, 0, ',', '.');
            }
            else{
                $packageName = '>10.000 Pasukan';
            }

            return [$package => $packageName];
        });

    }
    public function getPriceRupiahAttribute()
    {
        $conversionRate = ConversionRate::first()?->conversion_rate ?? 0;
        return 'Rp. ' . number_format($this->price * $conversionRate, 0, ',', '.');
    }
    public function getRewardRupiahAttribute()
    {
        $conversionRate = ConversionRate::first()?->conversion_rate ?? 0;
        return 'Rp. ' . number_format($this->reward * $conversionRate, 0, ',', '.');
    }
}
