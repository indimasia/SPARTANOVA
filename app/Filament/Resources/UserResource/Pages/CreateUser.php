<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = static::getModel()::create($data);

        if (isset($data)) {
            $socialMedias = [
                'Instagram' => $data['instagram'] ?? null,
                'TikTok' => $data['tiktok'] ?? null,
                'Youtube' => $data['youtube'] ?? null,
                'Facebook' => $data['facebook'] ?? null,
                'Twitter' => $data['twitter'] ?? null,
                'Google' => $data['google'] ?? null,
                'WhatsApp' => $data['whatsapp'] ?? null,
            ];

            foreach ($socialMedias as $sosisal_media => $account) {
                if ($account) {
                    \App\Models\SosialMediaAccount::create([
                        'user_id' => $user->id,
                        'sosial_media' => $sosisal_media,
                        'account' => $account,


                    ]);
                }
            }
        }

        return $user;
        }

}
