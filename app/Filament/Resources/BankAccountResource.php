<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\BankAccount;
use App\Models\TopUpTransaction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BankAccountResource\Pages;
use App\Filament\Resources\BankAccountResource\RelationManagers;

class BankAccountResource extends Resource
{
    protected static ?string $model = TopUpTransaction::class;

    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Akun Bank';
    protected static ?string $modelLabel = 'Akun Bank';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Card::make()
                 ->schema([
                    TextInput::make('nama_bank')
                    ->label('Bank')
                    ->string()
                    ->required(),


                    TextInput::make('no_rekening')
                        ->label('Nomor Rekening')
                        ->string()
                        ->required(),
                    
                    
                    TextInput::make('nama_pemilik')
                        ->label('Pemilik')
                        ->string()
                        ->required(),
                    ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bank')->label('Bank'),
                TextColumn::make('no_rekening')->label('Nomor Rekening'),
                TextColumn::make('nama_pemilik')->label('Pemilik'),
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
            'index' => Pages\ListBankAccounts::route('/'),
            'create' => Pages\CreateBankAccount::route('/create'),
            'edit' => Pages\EditBankAccount::route('/{record}/edit'),
        ];
    }
}
