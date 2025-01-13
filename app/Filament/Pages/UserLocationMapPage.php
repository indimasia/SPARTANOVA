<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;

class UserLocationMapPage extends Page
{
    protected static string $view = 'filament.pages.user-location-map-page';

    public function getUserLocations()
    {
        return User::role('pasukan')->whereNotNull('province_kode')
            ->whereNotNull('regency_kode')
            ->whereNotNull('district_kode')
            ->whereNotNull('village_kode')
            ->with(['province', 'regency', 'district', 'village'])
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'province' => $user->province->nama ?? '-',
                    'regency' => $user->regency->nama ?? '-',
                    'district' => $user->district->nama ?? '-',
                    'village' => $user->village->nama ?? '-',
                    'latitude' => $user->latitude ?? 0,
                    'longitude' => $user->longitude ?? 0,
                ];
            });
    }
}
