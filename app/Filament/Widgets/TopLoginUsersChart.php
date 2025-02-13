<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class TopLoginUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Pasukan Paling Rajin/Aktif';
    protected static string $color = 'primary';

    protected function getData(): array
    {
        $users = User::role('pasukan')->orderByDesc('login_count')->limit(10)->get();

        return [
            'datasets' => [
                [
                    'label' => 'Login Count',
                    'data' => $users->pluck('login_count')->toArray(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#8A2BE2', '#32CD32', '#FFD700', '#DC143C'],
                ],
            ],
            'labels' => $users->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
