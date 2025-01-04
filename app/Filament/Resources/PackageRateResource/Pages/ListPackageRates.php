<?php

namespace App\Filament\Resources\PackageRateResource\Pages;

use App\Filament\Resources\PackageRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackageRates extends ListRecords
{
    protected static string $resource = PackageRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
