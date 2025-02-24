<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Reward;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RewardResource\Pages;

class RewardResource extends Resource
{
    protected static ?string $model = Reward::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            Textarea::make('description')->nullable(),
            FileUpload::make('image')->disk('r2')->image()
            ->disk('r2')
            ->directory('reward/')
            ->visibility('public'),
            TextInput::make('quantity')->numeric()->required(),
            Select::make('rarity')
                ->options([
                    'common' => 'Common',
                    'rare' => 'Rare',
                    'epic' => 'Epic',
                    'legendary' => 'Legendary',
                ])->required(),
            TextInput::make('probability')->numeric()->step(0.01)->required(),
            Select::make('status')
                ->options([
                    'available' => 'Available',
                    'out_of_stock' => 'Out of Stock',
                    'hidden' => 'Hidden',
                ])->default('available')->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('name')->sortable()->searchable(),
            ImageColumn::make('image')->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')
            ->size(50)
            ->getStateUsing(fn ($record) => $record->image 
                ? URL::route('storage.fetch', ['filename' => $record->image]) 
                : null),
            TextColumn::make('quantity')->sortable(),
            TextColumn::make('rarity')->sortable(),
            TextColumn::make('probability')->sortable(),
            TextColumn::make('status')->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            SelectFilter::make('rarity')->options([
                'common' => 'Common',
                'rare' => 'Rare',
                'epic' => 'Epic',
                'legendary' => 'Legendary',
            ]),
            SelectFilter::make('status')->options([
                'available' => 'Available',
                'out_of_stock' => 'Out of Stock',
                'hidden' => 'Hidden',
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewards::route('/'),
            'create' => Pages\CreateReward::route('/create'),
            'edit' => Pages\EditReward::route('/{record}/edit'),
        ];
    }
}
