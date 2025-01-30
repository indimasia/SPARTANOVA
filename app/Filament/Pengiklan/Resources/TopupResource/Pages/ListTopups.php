<?php

namespace App\Filament\Pengiklan\Resources\TopupResource\Pages;

use App\Filament\Pengiklan\Resources\TopupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTopups extends ListRecords
{
    protected static string $resource = TopupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
