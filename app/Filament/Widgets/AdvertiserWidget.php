<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class AdvertiserWidget extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Advertiser';

    protected static ?string $pollingInterval = '60s';

    public ?string $filter = 'daily';

    protected function getFilters(): array
    {
        return [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
        ];
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        switch ($this->filter) {
            case 'daily':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $data[] = User::role('pengiklan')
                        ->whereDate('created_at', $date)
                        ->count();
                    $labels[] = $date->format('d M');
                }
                break;

            case 'weekly':
                for ($i = 3; $i >= 0; $i--) {
                    $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                    $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                    $data[] = User::role('pengiklan')
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->count();
                    $labels[] = 'Minggu ' . $startOfWeek->format('W');
                }
                break;

            case 'monthly':
                for ($i = 5; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $data[] = User::role('pengiklan')
                        ->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count();
                    $labels[] = $month->format('M Y');
                }
                break;

            case 'yearly':
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $data[] = User::role('pengiklan')
                        ->whereYear('created_at', $year)
                        ->count();
                    $labels[] = (string)$year;
                }
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah User Pengiklan',
                    'data' => $data,
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#388E3C',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    public function getType(): string
    {
        return 'line';
    }
}
