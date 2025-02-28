<?php

namespace App\Filament\Pengiklan\Resources;

use App\Filament\Pengiklan\Resources\NotificationResource\Pages;
use App\Models\Notification;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;

    // protected static ?string $navigationGroup = 'Atur Misi';
    protected static ?string $navigationLabel = 'Notifikasi';
    protected static ?int $navigationSort = 1;
    // protected static ?string $recordTitleAttribute = 'Job Campaign';
    protected static ?string $pluralModelLabel = 'Notifikasi';
    protected static ?string $pluralLabel = 'Notifikasi';
    protected static ?string $modelLabel = 'Notifikasi';
    protected static ?string $label = 'Notifikasi';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('notifiable_id', auth()?->id())->whereNull('read_at');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->notifications()->exists() ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }


    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('message') 
            ->label('Notifikasi Terbaru')
            ->getStateUsing(function ($record) {
                $data = json_decode($record->data, true);
                
                if (!empty($data['jobCampaign_id'])) {
                    return \App\Models\JobCampaign::where('id', intval($data['jobCampaign_id']))
                        ->value('description') ?: 'No description';
                }

                return 'No description';
            })
            ->description(fn ($record): string => json_decode($record->data, true)['message'] ?? 'No message', 'above')
            ->limit(110)
            ->tooltip(function (TextColumn $column): ?string {
                $state = $column->getState();
         
                if (strlen($state) <= $column->getCharacterLimit()) {
                    return null;
                }

                return $state;
            }),

            

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->size('100px')
                    ->since(),
            ])
            ->actions([
                Action::make('Tandai Dibaca')
                ->action(fn ($record) => $record->update(['read_at' => now()])) 
                ->icon('heroicon-o-check'),
            ])
            
            ;
    }

    public static function getNavigationBadge(): ?string
    {
        return auth()->user()->unreadNotifications()->count() ?: null;
    }



    public static function getRelations(): array
    {
        return [
           //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            // 'create' => Pages\CreateNotification::route('/create'),
            // 'edit' => Pages\EditNotification::route('/{record}/edit'),
        ];
    }
}
