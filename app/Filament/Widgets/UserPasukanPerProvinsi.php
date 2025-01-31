<?php

namespace App\Filament\Widgets;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;
use App\Models\User;
use App\Enums\UserRole;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UserPasukanPerProvinsi extends BaseWidget
{
    public ?string $activeProvince = null;
    public ?string $activeRegency = null;
    public ?string $activeDistrict = null;

    protected function getCards(): array
    {
        $cards = [];

        // Menentukan judul level yang sedang ditampilkan
        if ($this->activeDistrict) {
            $district = District::where('kode', $this->activeDistrict)->first();
            $cards[] = $this->makeTitleCard('Kecamatan:'," {$district->nama}");
            return array_merge($cards, $this->getVillageCards($this->activeDistrict));
        }

        if ($this->activeRegency) {
            $regency = Regency::where('kode', $this->activeRegency)->first();
            $cards[] = $this->makeTitleCard('Kabupaten/Kota:',$regency->nama);
            return array_merge($cards, $this->getDistrictCards($this->activeRegency));
        }

        if ($this->activeProvince) {
            $province = Province::where('kode', $this->activeProvince)->first();
            $cards[] = $this->makeTitleCard('Provinsi:',$province->nama);
            return array_merge($cards, $this->getRegencyCards($this->activeProvince));
        }

        $cards[] = $this->makeTitleCard('Pemetaan berdasarkan provinsi','');
        return array_merge($cards, $this->getProvinceCards());
    }

    private function makeTitleCard(string $label, string $title): Card
    {
        return Card::make($label, $title)
            ->color('info')
            ->extraAttributes([
                'style' => 'font-size: 24px; font-weight: bold;', // Perbesar teks dan buat tebal
            ]);
    }


    private function getProvinceCards(): array
    {
        return Province::all()->map(function ($province) {
            return Card::make($province->nama, $this->countUsersInProvince($province->kode))
                ->description('Klik untuk melihat daftar kabupaten/kota')
                ->chart([$this->countUsersInProvince($province->kode)])
                ->extraAttributes([
                    'wire:click' => "\$set('activeProvince', '{$province->kode}')",
                    'class' => 'cursor-pointer',
                ]);
        })->toArray();
    }

    private function getRegencyCards($provinceKode): array
    {
        $cards = Regency::where('prov_kode', $provinceKode)->get()->map(function ($regency) {
            return Card::make($regency->nama, $this->countUsersInRegency($regency->kode))
                ->description('Klik untuk melihat daftar kecamatan')
                ->chart([$this->countUsersInRegency($regency->kode)])
                ->extraAttributes([
                    'wire:click' => "\$set('activeRegency', '{$regency->kode}')",
                    'class' => 'cursor-pointer',
                ]);
        })->toArray();

        return $this->addBackButton($cards, 'activeProvince', null);
    }

    private function getDistrictCards($regencyKode): array
    {
        $cards = District::where('regency_kode', $regencyKode)->get()->map(function ($district) {
            return Card::make($district->nama, $this->countUsersInDistrict($district->kode))
                ->description('Klik untuk melihat daftar desa/kelurahan')
                ->chart([$this->countUsersInDistrict($district->kode)])
                ->extraAttributes([
                    'wire:click' => "\$set('activeDistrict', '{$district->kode}')",
                    'class' => 'cursor-pointer',
                ]);
        })->toArray();

        return $this->addBackButton($cards, 'activeRegency', null);
    }

    private function getVillageCards($districtKode): array
    {
        $cards = Village::where('district_kode', $districtKode)->get()->map(function ($village) {
            return Card::make($village->nama, $this->countUsersInVillage($village->kode))
                ->chart([$this->countUsersInVillage($village->kode)]);
        })->toArray();

        return $this->addBackButton($cards, 'activeDistrict', null);
    }

    private function countUsersInProvince($provinceKode): int
    {
        return User::where('province_kode', $provinceKode)
            ->whereHas('roles', function ($query) {
                $query->where('name', UserRole::PASUKAN->value);
            })->count();
    }

    private function countUsersInRegency($regencyKode): int
    {
        return User::where('regency_kode', $regencyKode)
            ->whereHas('roles', function ($query) {
                $query->where('name', UserRole::PASUKAN->value);
            })->count();
    }

    private function countUsersInDistrict($districtKode): int
    {
        return User::where('district_kode', $districtKode)
            ->whereHas('roles', function ($query) {
                $query->where('name', UserRole::PASUKAN->value);
            })->count();
    }

    private function countUsersInVillage($villageKode): int
    {
        return User::where('village_kode', $villageKode)
            ->whereHas('roles', function ($query) {
                $query->where('name', UserRole::PASUKAN->value);
            })->count();
    }

    private function addBackButton(array $cards, string $stateVariable, ?string $value): array
    {
        $backCard = Card::make('Kembali', 'Lihat tingkat sebelumnya')
            ->color('danger')
            ->extraAttributes([
                'wire:click' => "\$set('$stateVariable', " . ($value === null ? 'null' : "'$value'") . ")",
                'class' => 'cursor-pointer',
            ]);

        array_unshift($cards, $backCard);
        return $cards;
    }
}
