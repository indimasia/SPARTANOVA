<?php

namespace App\Filament\Pengiklan\Widgets;

use App\Models\Wallet;
use Filament\Widgets\Widget;

class TopupWalletWidget extends Widget
{
    protected static string $view = 'filament.pengiklan.widgets.topup-wallet-widget';

    protected int | string | array $columnSpan = 'full';

    public function getTotalPoints(): int
    {
        return Wallet::where('user_id', auth()->id())->pluck('total_points')->first() ?? 0;
    }
}
