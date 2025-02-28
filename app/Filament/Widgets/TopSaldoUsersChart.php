<?php

namespace App\Filament\Widgets;

use App\Models\UserPerformance;
use Filament\Widgets\ChartWidget;

class TopSaldoUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Pasukan Paling Banyak Mendapatkan Reward';
    protected static string $color = 'primary';

    protected function getData(): array
    {
        $topSaldoUsers = UserPerformance::orderByDesc('total_reward')->limit(10)->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Reward',
                    'data' => $topSaldoUsers->pluck('total_reward')->toArray(),
                    'backgroundColor' => ['#fac424', '#fbca3a', '#fbd050', '#fcd666', '#fcdc7c', '#fde292', '#fde7a7', '#feedbd', '#fef3d3', '#fff9e9'],
                ],
            ],
            'labels' => $topSaldoUsers->pluck('user.name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
