<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class InterestPasukanChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Minat Pasukan';

    protected function getData(): array
    {
        $interests = User::role('pasukan')->pluck('interest')->flatten();
        $interestCounts = $interests->countBy();

        return [
            'datasets' => [
                [
                    'label' => 'Minat',
                    'data' => $interestCounts->values()->toArray(),
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                ],
            ],
            'labels' => $interestCounts->keys()->toArray(),
        ];
    }

    public function getType(): string
    {
        return 'pie';
    }
}
