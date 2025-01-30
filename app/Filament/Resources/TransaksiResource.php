<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Wallet;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    // protected static string $navigationGroup = 'Admin Management'; // Opsional, jika ingin membuat grup menu

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('amount')->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))->sortable(),
                TextColumn::make('bank_account')->label('Rekening Tujuan'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime(),
            ])
            ->filters([
                // Filter status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Transaction $record) {
                            try {
                                $record->update(['status' => 'approved']);
                            
                                Notification::make()
                                    ->title('Successfully approved')
                                    ->success()
                                    ->send();
                            
                                $wallet = Wallet::where('user_id', $record->user_id)->first();
                            
                                if ($wallet) {
                                    $wallet->update([
                                        'total_points' => $wallet->total_points + $record->amount / 1000,
                                    ]);
                                } else {
                                    Wallet::create([
                                        'user_id' => $record->user_id,
                                        'total_points' => $record->amount / 1000,
                                    ]);
                                }
                            
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Failed to approve')
                                    ->danger()
                                    ->send();
                            }
                            
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            try {
                                $record->update(['status' => 'rejected']);
                                Notification::make()
                                    ->title('Successfully rejected')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Failed to reject')
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])->label('Action'),
                    ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
        ];
    }
}
