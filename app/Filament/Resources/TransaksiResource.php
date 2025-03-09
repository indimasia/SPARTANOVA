<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Wallet;
use Filament\Forms\Form;
use App\Models\Transaksi;
use Filament\Tables\Table;
use App\Models\Transaction;
use App\Models\ConversionRate;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Notifications\UserApprovedNotification;
use App\Filament\Resources\TransaksiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiResource\RelationManagers;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Top Up';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereNull('type');
    }

    public static function getNavigationBadge(): ?string
    {
        return Transaction::where(function ($query) {
            $query->whereNull('type') // Ambil yang type NULL
                  ->orWhereNotIn('type', ['withdrawal']); // Ambil yang bukan withdrawal
        })->where('status', 'pending')->count();
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('amount')->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))->sortable(),
                TextColumn::make('TopUpTransactions.nama_bank')->label('Rekening Tujuan'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                ImageColumn::make('transfer_proof')->label('Bukti Transfer')->disk('r2')->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')->size(50)->getStateUsing(fn ($record) => $record->transfer_proof 
                    ? URL::route('storage.fetch', ['filename' => $record->transfer_proof]) 
                    : null),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime(),
            ])
            ->filters([
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
                    
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Transaction $record) {
                            try {
                                $record->update(['status' => 'approved']);
                                $record->user->notify(new UserApprovedNotification(
                                    'Top Up Approved',
                                    'Top up has been approved!',
                                    '/dashboard'
                                ));
                                Notification::make()
                                    ->title('Successfully approved')
                                    ->success()
                                    ->send();
                            
                                $wallet = Wallet::where('user_id', $record->user_id)->first();
                                $conversionRate = ConversionRate::first();
                                $conversionRateValue = $conversionRate->conversion_rate;
                                if ($wallet) {
                                    $wallet->update([
                                        'total_points' => $wallet->total_points + $record->amount / $conversionRateValue,
                                    ]);
                                } else {
                                    Wallet::create([
                                        'user_id' => $record->user_id,
                                        'total_points' => $record->amount / $conversionRateValue,
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
                                $record->user->notify(new UserApprovedNotification(
                                    'Top Up Rejected',
                                    'Top up has been rejected!',
                                    '/dashboard'
                                ));
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                ImageEntry::make('transfer_proof')->label('Bukti Transfer')->disk('r2')->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')->size(50)->getStateUsing(fn ($record) => $record->transfer_proof 
                ? URL::route('storage.fetch', ['filename' => $record->transfer_proof]) 
                : null),
                TextEntry::make('user.name')->label('User'),
                TextEntry::make('amount')->label('Amount'),
                TextEntry::make('bank_account')->label('Bank Account'),
                TextEntry::make('status')->label('Status'),
                TextEntry::make('created_at')->label('Created At'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
        ];
    }
}
