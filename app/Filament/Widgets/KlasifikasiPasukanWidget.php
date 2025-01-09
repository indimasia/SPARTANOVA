<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KlasifikasiPasukanWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

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
                    'data' => [
                        '' => $baby_boomers,
                    ],
                ],
                [
                    'label' => 'Gen X',
                    'backgroundColor' => '#F1FAEE',
                    'data' => [
                        '' => $gen_x,
                    ],
                ],
                [
                    'label' => 'Gen Y',
                    'backgroundColor' => '#A8DADC',
                    'data' => [
                        '' => $gen_y,
                    ],
                ],
                [
                    'label' => 'Gen Z',
                    'backgroundColor' => '#457B9D',
                    'data' => [
                        '' => $gen_z,
                    ],
                ],
                [
                    'label' => 'Gen Alpha',
                    'backgroundColor' => '#1D3557',
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
