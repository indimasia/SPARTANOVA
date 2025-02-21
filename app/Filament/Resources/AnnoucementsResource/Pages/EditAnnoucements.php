<?php

namespace App\Filament\Resources\AnnoucementsResource\Pages;

use App\Filament\Resources\AnnoucementsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnnoucements extends EditRecord
{
    protected static string $resource = AnnoucementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
