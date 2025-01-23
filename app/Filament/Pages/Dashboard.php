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

class Dashboard extends Page
{
    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    /**
     * @var view-string
     */
    protected static string $view = 'vendor.filament.pages.dashboard';

    public function getUserLocations()
    {
        return User::role('pasukan')->whereNotNull('province_kode')
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
            // dd($response->json());
        } catch (\Exception $e) {
            // Handle exception
            dd('Exception: ' . $e->getMessage());
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
            // dd($response->json());
        } catch (\Exception $e) {
            // Handle exception
            dd('Exception: ' . $e->getMessage());
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
            __('filament-panels::pages/dashboard.title');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return static::$navigationIcon
            ?? FilamentIcon::resolve('panels::pages.dashboard.navigation-item')
            ?? (Filament::hasTopNavigation() ? 'heroicon-m-home' : 'heroicon-o-home');
    }

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return Filament::getWidgets();
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('filament-panels::pages/dashboard.title');
    }

    public function viewData(): array
    {
        return [
            'userLocations' => $this->getUserLocations(),
        ];
    }
}
