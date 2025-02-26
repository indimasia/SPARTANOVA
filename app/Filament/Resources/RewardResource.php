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
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->disabled(fn ($get) => $get('name') === 'ZONK'),

            Textarea::make('description')
                ->nullable()
                ->disabled(fn ($get) => $get('description') === 'ZONK'),

            FileUpload::make('image')->disk('r2')->image()
            ->disk('r2')
            ->directory('reward/')
            ->visibility('public'),
            TextInput::make('quantity')->numeric(),
            TextInput::make('probability')->numeric()->step(0.01)->required(),
            Toggle::make('is_available')
                ->default(true)->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        $headerActions = [];
        if (\App\Models\Reward::where('is_available', 1)->sum('probability') != 1) {
            $headerActions[] = Tables\Actions\Action::make('info')
                ->label('⚠️ Total probability tidak 100% total probability saat ini: ' . \App\Models\Reward::where('is_available', 1)->sum('probability') . '%')
                ->color('gray')
                ->disabled();
        }
        return $table 
        ->headerActions($headerActions)
        ->columns([
            TextColumn::make('name')->sortable()->searchable(),
            ImageColumn::make('image')->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')
            ->size(50)
            ->getStateUsing(fn ($record) => $record->image 
                ? URL::route('storage.fetch', ['filename' => $record->image]) 
                : null),
            TextColumn::make('quantity')->sortable(),
            TextColumn::make('probability')->sortable(),
            TextColumn::make('is_available')->sortable()
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state ? 'Available' : 'Not Available'),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])->filters([
            
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
