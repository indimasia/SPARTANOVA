<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Auth\Login;
use App\Filament\Pages\Location;
use App\Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\GenderOverview;
use App\Http\Middleware\AdminMiddleware;
use App\Filament\Widgets\AdvertiserTable;
use App\Filament\Widgets\AgePasukanChart;
use App\Filament\Widgets\AdvertiserWidget;
use App\Filament\Widgets\UserGrowthWidget;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\JobTypeStatsWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\InterestPasukanChart;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Widgets\UserPasukanPerProvinsi;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use App\Filament\Widgets\KlasifikasiPasukanWidget;
use App\Livewire\spartavTag as LivewireSpartavTag;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\SpartavTagResource\Widgets\spartavTag;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->sidebarFullyCollapsibleOnDesktop()
            ->brandLogo(asset('images/spartav_logo.png'))
            ->brandLogoHeight('4rem')
            ->brandName('Spartav')
            ->registration()
            ->passwordReset()
            ->darkMode(false)
            ->profile(isSimple: false)
            ->login()
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->databaseNotifications()
            ->widgets([
                Widgets\AccountWidget::class,
                LivewireSpartavTag::class,
                // Widgets\FilamentInfoWidget::class,
                StatsOverviewWidget::class,
                UserGrowthWidget::class,
                KlasifikasiPasukanWidget::class,
                AdvertiserTable::class,
                JobTypeStatsWidget::class,
                AgePasukanChart::class,
                GenderOverview::class,
                InterestPasukanChart::class,
                AdvertiserWidget::class,
                UserPasukanPerProvinsi::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                AdminMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
