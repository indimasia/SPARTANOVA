<?php

namespace App\Filament\Pengiklan\Resources\JobResource\Pages;

use Filament\Actions;
use App\Exports\JobExport;

use Filament\Actions\Action;
use App\Exports\JobPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JobCampaignPdfExport;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Pengiklan\Resources\JobResource;

class ViewJob extends ViewRecord
{
    protected static string $resource = JobResource::class;

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->label('Edit Job')
                ->icon('heroicon-o-pencil')
                ->color('primary'),
        ];
    }

}
