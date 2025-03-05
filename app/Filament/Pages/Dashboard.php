<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Filament\Widgets\Widget;
use Filament\Facades\Filament;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Http;
use App\Filament\Widgets\GenderOverview;
use App\Filament\Widgets\AdvertiserTable;
use App\Filament\Widgets\AgePasukanChart;
use App\Filament\Widgets\TopWDUsersChart;
use Filament\Widgets\WidgetConfiguration;
use App\Filament\Widgets\AdvertiserWidget;
use App\Filament\Widgets\UserGrowthWidget;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Widgets\JobTypeStatsWidget;
use App\Filament\Widgets\TopLoginUsersChart;
use App\Filament\Widgets\TopSaldoUsersChart;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\InterestPasukanChart;
use App\Filament\Widgets\TopMissionUsersChart;
use App\Filament\Widgets\TopReferralUsersChart;
use App\Filament\Widgets\UserPasukanPerProvinsi;
use App\Filament\Widgets\KlasifikasiPasukanWidget;
use App\Livewire\spartavTag as LivewireSpartavTag;

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
        return [
            AccountWidget::class,
            LivewireSpartavTag::class,
            TopLoginUsersChart::class,
            TopMissionUsersChart::class,
            TopSaldoUsersChart::class,
            TopWDUsersChart::class,
            TopReferralUsersChart::class,
            KlasifikasiPasukanWidget::class,
            // Widgets\FilamentInfoWidget::class,
            StatsOverviewWidget::class,
            UserGrowthWidget::class,
            AdvertiserWidget::class,
            GenderOverview::class,
            AdvertiserTable::class,
            JobTypeStatsWidget::class,
            AgePasukanChart::class,
            InterestPasukanChart::class,
            // UserPasukanPerProvinsi::class,
        ];
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
