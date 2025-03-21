<?php

namespace App\Filament\Pengiklan\Pages;

use Filament\Pages\Page;
use Livewire\Attributes\On;
use Filament\Widgets\Widget;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\WidgetConfiguration;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;

class DashboardPengiklan extends Page
{
    protected static string $routePath = '/dashboard-pengiklan';

    protected static ?int $navigationSort = -2;

    /**
     * @var view-string
     */
    protected static string $view = 'vendor.filament.pages.dashboardpengiklan';

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }
    
    public static function getNavigationIcon(): string | Htmlable | null
    {
        return 'heroicon-m-home';
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
        return array_merge(
            Filament::getWidgets()
            );
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

    #[On('updateLocation')]
    public function updateLocation($latitude, $longitude)
    {
        $user = Auth::user();

        $user->update([
            'current_latitude' => $latitude,
            'current_longitude' => $longitude,
        ]);

        session()->flash('status', 'Lokasi berhasil diperbarui.');
    }
}
