<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversionRateResource\Pages;
use App\Models\ConversionRate;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Components\Button;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ConversionRateResource extends Resource
{
    protected static ?string $model = ConversionRate::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Konversi Poin';
    protected static ?string $pluralModelLabel = 'Konversi Poin';
    protected static ?string $modelLabel = 'Konversi Poin';


    public static function canCreate(): bool
    {
        $conversionRate = ConversionRate::all()->count();
        $conversionRate >=0 ? $result=false : $result=true;
        return $result;
    }



    public static function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            Card::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextInput::make('rupiah_amount')
                                ->label('Nominal (Rp)')
                                ->numeric()
                                ->required(),


                            TextInput::make('points_amount')
                                ->label('Jumlah Poin')
                                ->numeric()
                                ->required(),
                        ]),

                    TextInput::make('conversion_rate')
                        ->label('1 Poin = Berapa Rp?')
                        ->numeric()
                        ->disabled(),
                    TextInput::make('example_conversion')
                        ->label('Simulasi Pencairan')
                        ->disabled(),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('Simulasi Pencairan')
                                ->color('warning')
                                ->action(function (Forms\Get $get, Forms\Set $set) {
                                    $rupiahAmount = $get('rupiah_amount');
                                $pointsAmount = $get('points_amount');

                                // Perform calculations
                                if ($rupiahAmount && $pointsAmount) {
                                    $conversionRate = $rupiahAmount / $pointsAmount;
                                    $simulated = 250;
                                    $simulatedValue = $conversionRate * $simulated;
                                    $exampleConversion = 'Jika Anda menukarkan ' . $simulated . ' Poin, Anda akan menerima Rp ' . number_format($simulatedValue, 0, ',', '.') . ' sebagai hasil pencairan.';

                                    // Update form fields with the calculated values
                                    $set('conversion_rate', $conversionRate);
                                    $set('example_conversion', $exampleConversion);
                                }
                                })
                        ]),
                ]),
        ]);
}


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('rupiah_amount')->label('Nominal (Rp)')->money('IDR'),
                TextColumn::make('points_amount')->label('Jumlah Poin'),
                TextColumn::make('conversion_rate')->label('1 Poin = Rp')->formatStateUsing(fn ($state) => number_format($state)),
                TextColumn::make('created_at')->label('Dibuat')->date(),
            ])
            ->filters([])
            ->paginated(false)
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConversionRates::route('/'),
            // 'create' => Pages\CreateConversionRate::route('/create'),
            'edit' => Pages\EditConversionRate::route('/{record}/edit'),
        ];
    }
}
