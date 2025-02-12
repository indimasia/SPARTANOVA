<?php

namespace App\Filament\Resources\ConversionRateResource\Pages;

use App\Filament\Resources\ConversionRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConversionRates extends ListRecords
{
    protected static string $resource = ConversionRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
