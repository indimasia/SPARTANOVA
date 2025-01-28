<?php

namespace App\Filament\Resources\AdvetiserResource\Pages;

use App\Filament\Resources\AdvetiserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvetiser extends EditRecord
{
    protected static string $resource = AdvetiserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
