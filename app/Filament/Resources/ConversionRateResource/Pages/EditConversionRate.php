<?php

namespace App\Filament\Resources\ConversionRateResource\Pages;

use App\Filament\Resources\ConversionRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConversionRate extends EditRecord
{
    protected static string $resource = ConversionRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
