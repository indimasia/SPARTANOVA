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
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#8A2BE2', '#32CD32', '#FFD700', '#DC143C'],
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
