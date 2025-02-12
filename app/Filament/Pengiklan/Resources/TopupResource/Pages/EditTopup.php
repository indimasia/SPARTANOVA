<?php

namespace App\Filament\Pengiklan\Resources\TopupResource\Pages;

use App\Filament\Pengiklan\Resources\TopupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTopup extends EditRecord
{
    protected static string $resource = TopupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
