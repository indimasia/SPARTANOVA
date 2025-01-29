<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Widgets\Widget;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Http;
use Filament\Widgets\WidgetConfiguration;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;

class Location extends Page
{
    protected static string $routePath = '/location';

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Location';

    protected static ?int $navigationSort = 1;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.pages.location';

    public function getUserLocations()
{
    return User::whereHas('roles', function ($query) {
        $query->where('name', 'pengiklan')
              ->orWhere('name', 'pasukan');
    })
    ->whereNotNull('province_kode')
    ->whereNotNull('regency_kode')
    ->whereNotNull('district_kode')
    ->whereNotNull('village_kode')
    ->whereNotNull('current_latitude')
    ->whereNotNull('current_longitude')
    ->with(['province', 'regency', 'district', 'village'])
    ->get()
    ->map(function ($user) {
        $lokasisaatini = $this->getLocationDetails($user->current_latitude, $user->current_longitude);
        $lokasiregister = $this->getLocationDetailsRegister($user->latitude, $user->longitude);
        return [
            'name' => $user->name,
            'phone' => $user->phone,
            'role' => $user->roles->first()->name ?? '-', 
            'lokasisaatini' => $lokasisaatini['display_name'] ?? '-',
            'lokasiregister' => $lokasiregister['display_name'] ?? '-',
            'latitude' => $user->latitude ?? 0,
            'longitude' => $user->longitude ?? 0,
            'current_latitude' => $user->current_latitude ?? 0,
            'current_longitude' => $user->current_longitude ?? 0,
        ];
    });
}


    public function getLocationDetails($current_latitude, $current_longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$current_latitude}&lon={$current_longitude}&zoom=18&addressdetails=1";
                        
            $response = Http::withHeaders([
                'User-Agent' => config('app.name'), // atau gunakan nama aplikasi Anda
            ])->get($url);
        } catch (\Exception $e) {
            return null;
        }

        if ($response->successful()) {
            $data = $response->json();

            return [
                'display_name' => $data['display_name'] ?? null,
            ];
        }

        return null;
    }
    public function getLocationDetailsRegister($latitude, $longitude)
    {
        try {
            $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";
                        
            $response = Http::withHeaders([
                'User-Agent' => config('app.name'), // atau gunakan nama aplikasi Anda
            ])->get($url);
        } catch (\Exception $e) {
            return null;
        }

        if ($response->successful()) {
            $data = $response->json();

            return [
                'display_name' => $data['display_name'] ?? null,
            ];
        }

        return null;
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ??
            static::$title ??
            __('filament-panels::pages/location.title');
    }

    public function getActiveUserCounts()
    {
        return [
            'pengiklan' => User::whereHas('roles', function ($query) {
                $query->where('name', 'pengiklan');
            })->where('is_active', true)->count(),

            'pasukan' => User::whereHas('roles', function ($query) {
                $query->where('name', 'pasukan');
            })->where('is_active', true)->count(),
        ];
    }

}
