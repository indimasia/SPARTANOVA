<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\JobCampaign;
use Carbon\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $activeJobsCount = JobCampaign::where('status', 'publish')
            ->where('end_date', '>', Carbon::now())
            ->count();
        $newActiveJobsToday = JobCampaign::where('status', 'publish')
            ->where('end_date', '>', now())
            ->whereDate('created_at', $today)
            ->count();

        $totalPasukan = User::role('Pasukan')->count();
        $newPasukanToday = User::role('Pasukan')
            ->whereDate('created_at', $today)
            ->count();

        $totalPengiklan = User::role('Pengiklan')->count();
        $newPengiklanToday = User::role('Pengiklan')
            ->whereDate('created_at', $today)
            ->count();

        return [
            Stat::make('Pekerjaan Aktif', $activeJobsCount)
                ->description("+" . $newActiveJobsToday . " hari ini")
                ->descriptionIcon('heroicon-o-clock')
                ->color('success'),

            Stat::make('Pasukan', $totalPasukan)
                ->description("+" . $newPasukanToday . " hari ini")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Pengiklan', $totalPengiklan)
                ->description("+" . $newPengiklanToday . " hari ini")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
