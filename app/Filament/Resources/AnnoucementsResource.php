<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Annoucement;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AnnoucementsResource\Pages;
use App\Filament\Resources\AnnoucementsResource\RelationManagers;

class AnnoucementsResource extends Resource
{
    protected static ?string $model = Annoucement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                ->required()
                ->maxLength(255),
                TextInput::make('content')
                ->required()
                ->maxLength(225),
                FileUpload::make('image')
                ->required()
                ->imageEditor()
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('1:1')
                ->disk('r2')
                ->directory('admin/annoucements'),
                Select::make('role')
                ->options([
                    'pengiklan' => 'pengiklan',
                    'pasukan' => 'pasukan',
                    'both' => 'both'
                ]),
                DateTimePicker::make('start_date')
                ->nullable(),
                DateTimePicker::make('end_date')
                ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                ->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')
                ->size(100)
                ->getStateUsing(fn ($record) => $record->image 
                    ? URL::route('storage.fetch', ['filename' => $record->image]) 
                    : null),
                TextColumn::make('title'),
                TextColumn::make('content'),
                TextColumn::make('role'),
                TextColumn::make('start_date'),
                TextColumn::make('end_date'),
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
            'index' => Pages\ListAnnoucements::route('/'),
            'create' => Pages\CreateAnnoucements::route('/create'),
            'edit' => Pages\EditAnnoucements::route('/{record}/edit'),
        ];
    }
}
