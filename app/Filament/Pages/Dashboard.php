<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Widgets\Widget;
use Filament\Pages\Page;
use Filament\Facades\Filament;
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
