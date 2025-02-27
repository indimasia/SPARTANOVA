<?php

namespace App\Filament\Resources\AnnoucementsResource\Pages;

use App\Filament\Resources\AnnoucementsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnnoucements extends ListRecords
{
    protected static string $resource = AnnoucementsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
