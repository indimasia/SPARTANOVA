<?php

namespace App\Filament\Resources\AdvetiserResource\Pages;

use App\Filament\Resources\AdvetiserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdvetiser extends CreateRecord
{
    protected static string $resource = AdvetiserResource::class;

    public function afterCreate(): void
    {
        $this->record->assignRole('pengiklan');
    }
}
