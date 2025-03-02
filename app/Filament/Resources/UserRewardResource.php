<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRewardResource\Pages;
use App\Filament\Resources\UserRewardResource\RelationManagers;
use App\Models\Reward;
use App\Models\UserReward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRewardResource extends Resource
{
    protected static ?string $model = UserReward::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

      protected static ?string $navigationGroup = 'Master Data';

      protected static ?string $navigationLabel = 'Ruang Keberuntunggan';

      protected static ?string $pluralModelLabel = 'Ruang Keberuntunggan';
      protected static ?string $modelLabel = 'Ruang Keberuntunggan';

      protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(UserReward::where('reward_id', '!=', 1))
            ->columns([
                TextColumn::make('user.name')
                ->label('Nama')
                ->sortable(),
                TextColumn::make('reward.name')
                ->label('Hadiah'),
                TextColumn::make('reward.description')
                ->label('Deskripsi')
                ->formatStateUsing(fn ($record): string => $record->reward->description ?? 'No description available'),
                TextColumn::make('reward_quantity')
                ->label('Jumlah'),
                TextColumn::make('won_at')
                ->label('Menang Pada')
                ->dateTime('d F Y  G:i'),


            ])
            ->filters([
                
            ])
            ->actions([

                    ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
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
            'index' => Pages\ListUserRewards::route('/'),
            'create' => Pages\CreateUserReward::route('/create'),
            'edit' => Pages\EditUserReward::route('/{record}/edit'),
        ];
    }
}
