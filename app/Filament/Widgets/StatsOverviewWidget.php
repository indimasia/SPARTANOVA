<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\JobCampaign;
use App\Models\Transaction;
use App\Models\ConversionRate;
use App\Models\UserPerformance;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

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
        
        $conversionRate = ConversionRate::value('conversion_rate') ?? 1;
        $totalPoints = UserPerformance::sum('total_reward');

            // Hitung nominal dalam rupiah
        $totalNominal = $totalPoints * $conversionRate;

        $saldoAdvertiser = Wallet::sum('total_points');
        $totalNominalAdvertiser = $saldoAdvertiser * $conversionRate;

        $withdrawalSuccess = Transaction::where('type', 'withdrawal')->where('status', 'approved')->count();
        $jumlahWithdrawalSuccess = Transaction::where('type', 'withdrawal')->where('status', 'approved')->sum('amount');
        return [
            Stat::make('Misi Aktif', $activeJobsCount)
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
            Stat::make('Total Saldo User', number_format($totalPoints, 0, ',', '.'))
                ->description("Rp " . number_format($totalNominal, 0, ',', '.'))
                ->color('success'),
            Stat::make('Total Saldo Advertiser', number_format($saldoAdvertiser, 0, ',', '.'))
                ->description("Rp " . number_format($totalNominalAdvertiser, 0, ',', '.'))
                ->color('success'),
            Stat::make('Withdrawal', "Rp " . number_format($jumlahWithdrawalSuccess, 0, ',', '.'))
                ->description($withdrawalSuccess . ' Transaksi')
                ->color('success'),
        ];
    }
}
