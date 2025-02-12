<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\UserPasukanPerProvinsi;

class PemetaanPasukan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.pemetaan-pasukan';

    protected function getHeaderWidgets(): array
    {
        return [
            UserPasukanPerProvinsi::class, // Menambahkan widget ke halaman
        ];
    }

}
