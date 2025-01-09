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
        $activeJobsCount = JobCampaign::where('status', 'publish')
            ->where('end_date', '>', Carbon::now())
            ->count();

        return [
            Stat::make('Pekerjaan Aktif', $activeJobsCount)
                ->description('Jumlah pekerjaan yang aktif ')
                ->descriptionIcon('heroicon-o-clock')
                ->color('success'),
        ];
    }
}
