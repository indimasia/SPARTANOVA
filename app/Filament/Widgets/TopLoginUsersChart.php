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
                    'backgroundColor' => ['#E81919', '#EA3333', '#ED4d4d', '#EF6666', '#F28080', '#F59999', '#FF9F40', '#F7B3B3', '#FACCCC', '#FCE6E6', '#FFFFFF'],
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
