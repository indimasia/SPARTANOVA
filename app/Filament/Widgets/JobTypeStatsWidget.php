<?php

namespace App\Filament\Widgets;

use App\Models\JobCampaign;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class JobTypeStatsWidget extends BaseWidget
{
    protected static ?string $heading = 'Statistik Jenis Misi';

    protected int | string | array $columnSpan = '1';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JobCampaign::query()
                ->select('type')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw("MD5(type) as id")
                ->groupBy('type')
                ->orderByDesc('total')
            )->columns([
                TextColumn::make('type')
                    ->label('Jenis Pekerjaan')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('total')
                    ->label('Jumlah')
                    ->sortable()
                    ->alignCenter(),
            ]);
    }
}
