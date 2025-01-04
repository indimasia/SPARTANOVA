<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\District;
use App\Models\Regency;
use App\Models\User;
use App\Models\Village;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($context) => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->placeholder(function (string $operation) {
                        if ($operation === 'edit') {
                            return 'Isi untuk mengubah password';
                        }
                    })
                    ->hint(function (string $operation) {
                        if ($operation === 'edit') {
                            return 'Biarkan kosong jika tidak ingin mengubah password';
                        }
                    })
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->options([
                        'L' => 'Laki-Laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('province_kode')
                    ->relationship('province', 'nama')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('regency_kode')
                    ->relationship('regency', 'nama')
                    ->preload()
                    ->searchable()
                    ->options(function(Get $get){
                        return Regency::where('prov_kode', $get('province_kode'))->whereNotNull(['nama','kode'])
                            ->pluck('nama', 'kode');
                    })
                    ->live()
                    ->required(),
                Forms\Components\Select::make('district_kode')
                    ->relationship('district', 'nama')
                    ->preload()
                    ->searchable()
                    ->options(function(Get $get){
                        return District::where('regency_kode', $get('regency_kode'))->whereNotNull(['nama','kode'])
                            ->pluck('nama', 'kode');
                    })
                    ->live()
                    ->required(),
                Forms\Components\Select::make('village_kode')
                    ->relationship('village', 'nama')
                    ->preload()
                    ->searchable()
                    ->options(function(Get $get){
                        return Village::where('district_kode', $get('district_kode'))->whereNotNull(['nama','kode'])
                            ->pluck('nama', 'kode');
                    })
                    ->live()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('province.nama'),
                Tables\Columns\TextColumn::make('regency.nama'),
                Tables\Columns\TextColumn::make('district.nama'),
                Tables\Columns\TextColumn::make('village.nama'),
                Tables\Columns\TextColumn::make('roles.name')
                ->badge(),
                Tables\Columns\TextColumn::make('gender')
                ->state(fn($record) => $record->gender == 'L' ? 'Laki-Laki' : 'Perempuan')
                ->color(fn($record) => $record->gender == 'L' ? 'warning' : 'danger'),

                Tables\Columns\TextColumn::make('date_of_birth')->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
