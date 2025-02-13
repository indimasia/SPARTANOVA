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
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#8A2BE2', '#32CD32', '#FFD700', '#DC143C'],
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
