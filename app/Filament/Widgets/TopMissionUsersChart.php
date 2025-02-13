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
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#8A2BE2', '#32CD32', '#FFD700', '#DC143C'],
                    
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
