<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class TopWDUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Pasukan Paling Banyak WD';

    protected function getData(): array
    {
        $topWDUsers = Transaction::where('type', 'withdrawal')
            ->selectRaw('user_id, SUM(amount) as total_amount')
            ->groupBy('user_id')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->with('user') // Pastikan relasi user di-load untuk mendapatkan nama
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Withdraw',
                    'data' => $topWDUsers->pluck('total_amount')->toArray(),
                    'backgroundColor' => ['#6488ea', '#7494ec', '#83a0ee', '#93acf0', '#a2b8f2', '#b2c4f5', '#c1cff7', '#d1dbf9', '#e0e7fb', '#f0f3fd'],
                ],
            ],
            'labels' => $topWDUsers->pluck('user.name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
