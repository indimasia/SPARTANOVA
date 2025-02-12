<?php

namespace App\Filament\Pengiklan\Resources\TopupResource\Pages;

use App\Filament\Pengiklan\Resources\TopupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTopup extends CreateRecord
{
    protected static string $resource = TopupResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        return $data;
    }
}
