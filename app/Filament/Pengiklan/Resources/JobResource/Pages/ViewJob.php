<?php

namespace App\Filament\Pengiklan\Resources\JobResource\Pages;

use App\Filament\Pengiklan\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Filament\Pages\Actions\EditAction;
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
