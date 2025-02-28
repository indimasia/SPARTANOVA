<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopReferralUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Top 10 Referral Users';
    protected static string $color = 'primary';

    protected function getData(): array
    {
        $topReferralUsers = User::select('referred_by', DB::raw('COUNT(*) as total'))
            ->whereNotNull('referred_by')
            ->groupBy('referred_by')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Referral',
                    'data' => $topReferralUsers->pluck('total')->toArray(),
                    'backgroundColor' => ['#977a59', '#a2896b', '#ae977e', '#b9a690', '#c5b5a3', '#d1c4b5', '#dcd3c8', '#e8e1da', '#f3f0ed', '#ffffff'],
                ],
            ],
            'labels' => $topReferralUsers->pluck('referred_by')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
