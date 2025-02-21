<?php

namespace App\Providers\Filament;

use App\Filament\Pengiklan\Widgets\Announcement;
use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\DataJobWidget;
use App\Filament\Pengiklan\Pages\DashboardPengiklan;
use Filament\Http\Middleware\Authenticate;
use App\Http\Middleware\PengiklanMiddleware;
use App\Livewire\spartavTag;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class PengiklanPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pengiklan')
            ->path('pengiklan')
            ->brandLogo(asset('images/spartav_logo.png'))
            ->brandLogoHeight('4rem')
            ->brandName('Spartav')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Pengiklan/Resources'), for: 'App\\Filament\\Pengiklan\\Resources')
            ->discoverPages(in: app_path('Filament/Pengiklan/Pages'), for: 'App\\Filament\\Pengiklan\\Pages')
            ->pages([
                DashboardPengiklan::class,
            ])
            ->databaseNotifications()
            ->discoverWidgets(in: app_path('Filament/Pengiklan/Widgets'), for: 'App\\Filament\\Pengiklan\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                spartavTag::class,
                // Widgets\FilamentInfoWidget::class,
                DataJobWidget::class,
                Announcement::class
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
                PengiklanMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
