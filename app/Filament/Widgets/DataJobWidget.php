<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\JobCampaign;
use Carbon\Carbon;

class DataJobWidget extends BaseWidget
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

        return [
            Stat::make('Pekerjaan Aktif', $activeJobsCount)
                ->description("+" . $newActiveJobsToday . " hari ini")
                ->descriptionIcon('heroicon-o-clock')
                ->color('success'),
        ];
    }
}
