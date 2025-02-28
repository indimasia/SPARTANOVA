<?php

namespace App\Filament\Resources\ArticlesResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Models\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ArticleResource;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function afterCreate(): void
    {
        $users = User::role('pasukan')->get();
        foreach ($users as $user) {
            $user->notify(new UserApprovedNotification(
                'Article Created',
                'New article has been created!',
                '/dashboard'
            ));
        }
        // Simpan satu notifikasi untuk semua user dengan role 'pasukan'
        Notification::create([
            'id' => (string) Str::uuid(),
            'type' => 'Article Created',
            'notifiable_id' => $this->record->id, // Tidak terikat ke satu user
            'notifiable_type' => Article::class, // Tanda bahwa ini untuk semua user
            'data' => json_encode([
                'message' => 'New article has been created!',
                'article_id' => $this->record->id,
            ]),
            'read_at' => null, // Jadi semua user bisa cek apakah sudah baca atau belum
        ]);
    }
}
