<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class InterestPasukanChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Minat Pasukan';

    protected function getData(): array
    {
        $interests = User::role('pasukan')
            ->pluck('interest') 
            ->map(function ($interest) {
                if (is_null($interest) || $interest === '' || $interest === '[]') {
                    return ['Tidak Memiliki Minat'];
                }
                return is_array($interest) ? $interest : json_decode($interest, true);
            })
            ->flatten();

        $interestCounts = $interests->countBy();

        $labels = $interestCounts->keys()->toArray();
        $data = $interestCounts->values()->toArray();
        $colors = array_map(fn($index) => $this->generateFixedColor($index), array_keys($labels));

        return [
            'datasets' => [
                [
                    'label' => 'Minat',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                    'borderColor' => 'rgba(0,0,0,0)',
                ],
            ],
            'labels' => $labels
        ];
    }

    public function getColumnSpan(): int | string | array
    {
        return 'full'; 
    }

    private function generateFixedColor(int $index): string
    {
        $fixedColors = [
            '#E6194B', '#3CB44B', '#FFE119', '#0082C8', '#F58231',
            '#911EB4', '#46F0F0', '#F032E6', '#D2F53C', '#FABEBE',
            '#008080', '#E6BEFF', '#AA6E28', '#800000', '#AaffC3',
            '#808000', '#FFD8B1', '#000080', '#808080', '#FFFFFF',
            '#FF6347', '#32CD32', '#FF4500', '#6A5ACD', '#D2691E',
            '#FF1493', '#8A2BE2', '#A52A2A', '#5F9EA0', '#7FFF00',
            '#D3D3D3', '#FF8C00', '#A0522D', '#800080', '#FFFF00',
            '#00FF00', '#008B8B', '#B8860B', '#FF1493', '#4B0082',
            '#FFD700', '#B0E0E6', '#BC8F8F', '#8B0000', '#FFD700',
            '#FF69B4', '#C71585', '#FF6347', '#00FA9A', '#DCDCDC'
        ];
        return $fixedColors[$index % count($fixedColors)];
    }


    public function getType(): string
    {
        return 'bar';
    }
}
