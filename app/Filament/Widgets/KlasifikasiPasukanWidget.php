<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KlasifikasiPasukanWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Generasi Pasukan';

    public function getData(): array
    {
        $baby_boomers = User::where('generation_category', '=', 'Baby Boomers')->count();
        $gen_x = User::where('generation_category', '=', 'Gen X')->count();
        $gen_y = User::where('generation_category', '=', 'Gen Y')->count();
        $gen_z = User::where('generation_category', '=', 'Gen Z')->count();
        $gen_alpha = User::where('generation_category', '=', 'Gen Alpha')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Baby Boomers',
                    'backgroundColor' => '#E63946',
                    'borderWidth' => 0,
                    'data' => [
                        '' => $baby_boomers,
                    ],
                ],
                [
                    'label' => 'Gen X',
                    'backgroundColor' => '#8ECAE6',
                    'borderWidth' => 0,
                    'data' => [
                        '' => $gen_x,
                    ],
                ],
                [
                    'label' => 'Gen Y',
                    'backgroundColor' => '#A8DADC',
                    'borderWidth' => 0,
                    'data' => [
                        '' => $gen_y,
                    ],
                ],
                [
                    'label' => 'Gen Z',
                    'backgroundColor' => '#457B9D',
                    'borderWidth' => 0,
                    'data' => [
                        '' => $gen_z,
                    ],
                ],
                [
                    'label' => 'Gen Alpha',
                    'backgroundColor' => '#1D3557',
                    'borderWidth' => 0,
                    'data' => [
                        '' => $gen_alpha,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
}
