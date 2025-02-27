<?php

namespace App\Filament\Resources\JobInAdminResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Exports\JobExport;
use Filament\Actions\Action;
use App\Exports\JobPdfExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\JobInAdminResource;
use App\Models\JobParticipant;

class jobDetail extends ViewRecord
{
    protected static string $resource = JobInAdminResource::class;


    protected function getHeaderActions(): array
    {
        // Gunakan $this->record untuk mendapatkan data dari model saat ini
        
        $endDate = $this->record->end_date ?? null;
        $peserta = $this->record->participant_count;
        $kuota   = $this->record->quota;
    
        // Perbaiki kondisi dengan tanda kurung untuk menghindari error evaluasi logika
        if (($endDate && now()->greaterThan(Carbon::parse($endDate))) || ($peserta >= $kuota)) {
            return [
                Action::make('Export Excel')
                    ->label('Export Excel')
                    ->color('success')
                    ->action(fn ($record) => Excel::download(new JobExport($record->id), $record->title . '.xlsx')),
                Action::make('Export PDF')
                    ->label('Export PDF')
                    ->color('primary')
                    ->action(fn ($record) => (new JobPdfExport($record->id))->download()),
            ];
        }
    
        return [];
    }
    
}
