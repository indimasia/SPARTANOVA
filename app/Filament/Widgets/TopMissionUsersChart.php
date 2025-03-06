<?php

namespace App\Filament\Widgets;

use App\Models\UserPerformance;
use Filament\Widgets\ChartWidget;

class TopMissionUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Pasukan Terbanyak Mengerjakan Misi';
    protected static string $color = 'primary';

    protected function getData(): array
    {
        $userperformances = UserPerformance::orderByDesc('job_completed')->limit(10)->get();

        return [
            'datasets' => [
                [
                    'label' => 'Job Completed',
                    'data' => $userperformances->pluck('job_completed')->toArray(),
                    'backgroundColor' => ['#19635B', '#33756d', '#4d8680', '#669792', '#80a9a4', '#99bab6', '#b3cbc8', '#ccdcdb', '#e6eeed', '#ffffff'],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $userperformances->pluck('user.name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
