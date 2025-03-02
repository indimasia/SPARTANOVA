<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SettingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SettingResource\RelationManagers;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2) // Membuat 2 kolom
                    ->schema([
                        Toggle::make('mini_game')
                            ->label('Status Mini Game')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(Setting::where('key_name', 'Mini Game')->value('value') === 'on')
                            ->live()
                            ->afterStateUpdated(fn ($state) => 
                                Setting::where('key_name', 'Mini Game')->update(['value' => $state ? 'on' : 'off'])
                            ),

                        TextInput::make('poin_game')
                            ->label('Jumlah Poin')
                            ->default(Setting::where('key_name', 'Poin Game')->value('value'))
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(fn ($state) => 
                                Setting::where('key_name', 'Poin Game')->update(['value' => $state])
                            ),

                        TextInput::make('minimum_withdraw')
                            ->label('Minimum Withdraw')
                            ->default(Setting::where('key_name', 'Minimum Withdraw')->value('value'))
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(fn ($state) => 
                                Setting::where('key_name', 'Minimum Withdraw')->update(['value' => $state])
                            ),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key_name')
                    ->label('Pengaturan')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state)))
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state)))
                    ->searchable(),
                
            ]);
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
            'edit' => Pages\EditSetting::route('/{record}/edit'),
            'index' => Pages\ListSettings::route('/'),
        ];
    }
}
