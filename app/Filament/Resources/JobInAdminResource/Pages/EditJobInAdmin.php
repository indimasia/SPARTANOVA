<?php

namespace App\Filament\Resources\JobInAdminResource\Pages;

use App\Filament\Resources\JobInAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobInAdmin extends EditRecord
{
    protected static string $resource = JobInAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
