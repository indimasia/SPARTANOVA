<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class GenderOverview extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Gender Pasukan';

    protected function getData(): array
    {
        $male = User::role('pasukan')->where('gender', 'L')->count();
        $female = User::role('pasukan')->where('gender', 'P')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Gender',
                    'data' => [$male, $female],
                    'backgroundColor' => ['#42A5F5', '#EC407A'],
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
        ];
    }

    public function getType(): string
    {
        return 'pie';
    }
}
