<?php

namespace App\Filament\Resources\WithdrawResource\Pages;

use Filament\Actions;
use App\Exports\JobExport;
use Filament\Actions\Action;
use App\Exports\WithdrawExport;
use App\Exports\WithdrawPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\WithdrawResource;


class ListWithdraws extends ListRecords
{
    protected static string $resource = WithdrawResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Export Excel')
                ->label('Export Excel')
                ->color('success')
                ->action(fn () => Excel::download(new WithdrawExport, 'withdraws.xlsx')),
            Action::make('Export PDF')
                ->label('Export PDF')
                ->color('primary')
                ->action(fn () => (new WithdrawPdfExport())->download()),
            
        ];
    }
}
