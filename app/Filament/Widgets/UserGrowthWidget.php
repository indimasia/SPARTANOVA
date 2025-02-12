<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\ChartWidget;

class UserGrowthWidget extends ChartWidget
{
    protected static ?string $heading = 'Pertumbuhan Pasukan';

    protected static ?string $pollingInterval = '60s'; // Refresh otomatis tiap 60 detik

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
                    $data[] = User::role('pasukan')
                        ->whereDate('created_at', $date)
                        ->count();
                    $labels[] = $date->format('d M');
                }
                break;

            case 'weekly':
                for ($i = 3; $i >= 0; $i--) {
                    $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                    $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                    $data[] = User::role('pasukan')
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                        ->count();
                    $labels[] = 'Minggu ' . $startOfWeek->format('W');
                }
                break;

            case 'monthly':
                for ($i = 5; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $data[] = User::role('pasukan')
                        ->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->count();
                    $labels[] = $month->format('M Y');
                }
                break;

            case 'yearly':
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $data[] = User::role('pasukan')
                        ->whereYear('created_at', $year)
                        ->count();
                    $labels[] = (string)$year;
                }
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah User Pasukan',
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
