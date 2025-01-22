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
            ->where('created_by', auth()->user()->id)
            ->count();
        $newActiveJobsToday = JobCampaign::where('status', 'publish')
            ->where('end_date', '>', now())
            ->whereDate('created_at', $today)
            ->where('created_by', auth()->user()->id)
            ->count();

        return [
            Stat::make('Misi Aktif', $activeJobsCount)
                ->description("+" . $newActiveJobsToday . " hari ini")
                ->descriptionIcon('heroicon-o-clock')
                ->color('success'),
        ];
    }
}
