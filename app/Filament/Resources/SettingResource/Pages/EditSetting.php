<?php

namespace App\Filament\Resources\SettingResource\Pages;

use Filament\Actions;
use App\Models\Setting;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SettingResource;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil semua setting dalam bentuk key => value
        $settings = Setting::pluck('value', 'key_name')->toArray();

        return [
            'mini_game' => $settings['Mini Game'] ?? 'off', // Ambil dari database
            'poin_game' => $settings['Poin Game'] ?? 0,
            'minimum_withdraw' => $settings['Minimum Withdraw'] ?? 0,
        ];
    }

}
