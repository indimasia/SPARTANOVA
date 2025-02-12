<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class AgePasukanChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Usia Pasukan';

    protected function getData(): array
    {
        $ageRanges = [
            '10-17' => [10, 17],
            '18-25' => [18, 25],
            '26-35' => [26, 35],
            '36-45' => [36, 45],
            '46+'   => [46, 100],
        ];
        $data = [];

        foreach ($ageRanges as $range => [$min, $max]) {
            $data[] = User::role('pasukan')
                ->whereBetween('date_of_birth', [
                    Carbon::now()->subYears($max),
                    Carbon::now()->subYears($min),
                ])->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Usia',
                    'data' => $data,
                    'borderColor' => 'transparent',
                    'backgroundColor' => ['#42A5F5', '#EC407A', '#FFA726', '#66BB6A'],
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => array_keys($ageRanges),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
