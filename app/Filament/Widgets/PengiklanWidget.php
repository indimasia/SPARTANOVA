<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PengiklanWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pengiklan', User::role('Pengiklan')->count())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
