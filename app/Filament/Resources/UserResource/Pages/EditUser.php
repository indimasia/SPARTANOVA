<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Models\SosialMediaAccount;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $socialMedia = $this->record->sosialMediaAccounts->pluck('account', 'sosial_media')->toArray();

        $data['instagram'] = $socialMedia['Instagram'] ?? null;
        $data['tiktok'] = $socialMedia['TikTok'] ?? null;
        $data['youtube'] = $socialMedia['Youtube'] ?? null;
        $data['facebook'] = $socialMedia['Facebook'] ?? null;
        $data['twitter'] = $socialMedia['Twitter'] ?? null;
        $data['google'] = $socialMedia['Google'] ?? null;
        $data['whatsapp'] = $socialMedia['WhatsApp'] ?? null;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = $this->record;
    
        $socialMedias = [
            'Instagram' => $data['instagram'] ?? null,
            'TikTok' => $data['tiktok'] ?? null,
            'Youtube' => $data['youtube'] ?? null,
            'Facebook' => $data['facebook'] ?? null,
            'Twitter' => $data['twitter'] ?? null,
            'Google' => $data['google'] ?? null,
            'WhatsApp' => $data['whatsapp'] ?? null,
        ];
    
        foreach ($socialMedias as $platform => $account) {
            if ($account !== null) {
                SosialMediaAccount::updateOrCreate(
                    ['user_id' => $user->id, 'sosial_media' => $platform],
                    ['account' => $account]
                );
            } else {
                SosialMediaAccount::updateOrCreate(
                    ['user_id' => $user->id, 'sosial_media' => $platform],
                    ['account' => 'Tidak punya akun']
                );
            }
    
        }
    
        return $data;
    }
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
