<?php

namespace App\Filament\Resources\JobInAdminResource\Pages;

use App\Filament\Resources\JobInAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobInAdmins extends ListRecords
{
    protected static string $resource = JobInAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
