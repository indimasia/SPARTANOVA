<?php

namespace App\Filament\Resources\JobInAdminResource\Pages;

use Filament\Actions;
use App\Exports\JobExport;
use Filament\Actions\Action;
use App\Exports\JobPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\JobInAdminResource;

class jobDetail extends ViewRecord
{
    protected static string $resource = JobInAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Export Excel')
                ->label('Export Excel')
                ->color('success')
                ->action(fn ($record) => Excel::download(new JobExport($record->id), $record->title . '.xlsx')),
            Action::make('Export PDF')
                ->label('Export PDF')
                ->color('primary')
                ->action(fn ($record) => (new JobPdfExport($record->id))->download()),
        ];
    }
}
