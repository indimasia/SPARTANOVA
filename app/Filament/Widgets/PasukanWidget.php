<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PasukanWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pasukan', User::role('Pasukan')->count())
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
