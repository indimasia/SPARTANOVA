<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use App\Models\ConversionRate;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\WithdrawResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WithdrawResource\RelationManagers;

class WithdrawResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Withdraw';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return Transaction::where('type', 'withdrawal')->where('status', 'pending')->count();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('type'),
                TextColumn::make('user.userPerformance.total_reward')
                    ->label('Poin Dimiliki')
                    ->color('success')
                    ->formatStateUsing(function ($state, $record) {
                        $conversionRate = ConversionRate::value('conversion_rate') ?? 1; // Default ke 1 untuk mencegah error
                        $amountPoints = $record->amount ? round($record->amount / $conversionRate) : 0; // Hitung poin dari amount
                        return ($state + $amountPoints) . ' Poin';
                    }),

                TextColumn::make('amount')
                    ->label('Poin Ditarik')
                    ->color('danger')
                    ->formatStateUsing(function ($state) {
                        $conversionRate = ConversionRate::value('conversion_rate') ?? 1; // Pastikan nilai tidak null
                        return $state ? round($state / $conversionRate) . ' Poin' : '-';
                    }),
                TextColumn::make('status')->color(fn ($record) => $record->status === 'pending' ? 'warning' : ($record->status === 'approved' ? 'success' : ($record->status === 'rejected' ? 'danger' : 'info'))),
                TextColumn::make('created_at'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),
                ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            $record->update(['status' => 'approved']);
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            $record->update(['status' => 'rejected']);
                        }),
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            $record->update(['status' => 'approved']);
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            $record->update(['status' => 'rejected']);
                        }),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                TextEntry::make('user.name'),
                TextEntry::make('amount'),
                TextEntry::make('status'),
                TextEntry::make('created_at'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'withdrawal');
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
            'index' => Pages\ListWithdraws::route('/'),
            // 'create' => Pages\CreateWithdraw::route('/create'),
            // 'view' => Pages\ViewWithdraw::route('/{record}'),
            // 'edit' => Pages\EditWithdraw::route('/{record}/edit'),
        ];
    }
}
