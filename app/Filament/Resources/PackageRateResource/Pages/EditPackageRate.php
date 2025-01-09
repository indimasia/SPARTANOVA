<?php

namespace App\Filament\Resources\PackageRateResource\Pages;

use App\Filament\Resources\PackageRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackageRate extends EditRecord
{
    protected static string $resource = PackageRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
