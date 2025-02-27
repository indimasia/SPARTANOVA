<?php

namespace App\Filament\Resources;

use App\Enums\JobType;
use App\Filament\Resources\PackageRateResource\Pages;
use App\Filament\Resources\PackageRateResource\RelationManagers;
use App\Models\PackageRate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackageRateResource extends Resource
{
    protected static ?string $model = PackageRate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Tarif Paket';
    protected static ?string $pluralModelLabel = 'Tarif Paket';
    protected static ?string $modelLabel = 'Tarif Paket';

    public static function canCreate(): bool
    {
       $jobType = JobType::optionsWithout();
       if (empty($jobType)) {
           return false;
       }
        return true;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                ->options(JobType::optionsWithout())
                ->unique(ignoreRecord: true)
                ->searchable()
                ->required(),
                Forms\Components\TextInput::make('price')
                ->numeric()
                ->required(),
                Forms\Components\TextInput::make('reward')
                ->numeric()
                ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        $conversionRate = \App\Models\ConversionRate::first()?->conversion_rate ?? 0;
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('price')->label('Harga (Poin)')->formatStateUsing(fn ($record) => $record->price . ' Poin'),
                Tables\Columns\TextColumn::make('price_rupiah')
                ->label('Harga (Rupiah)')
                ->sortable(),
                Tables\Columns\TextColumn::make('reward')
                ->label('Reward (Poin)')
                ->formatStateUsing(fn ($record) => number_format($record->reward, 0, ',', '.') . ' Poin'),

                Tables\Columns\TextColumn::make('reward_rupiah')
                ->label('Reward (Rupiah)')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackageRates::route('/'),
            // 'create' => Pages\CreatePackageRate::route('/create'),
            // 'edit' => Pages\EditPackageRate::route('/{record}/edit'),
        ];
    }
}
