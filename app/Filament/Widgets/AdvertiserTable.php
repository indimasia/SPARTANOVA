<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdvertiserTable extends BaseWidget
{
    protected static ?string $heading = 'Daftar Pengiklan';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::role('Pengiklan') // Query untuk mengambil user dengan role 'Pengiklan'
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Bergabung')
                    ->dateTime('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('generation_category')
                    ->label('Generasi')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc'); // Urutkan berdasarkan tanggal terbaru
    }
}
