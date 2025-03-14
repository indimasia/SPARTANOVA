<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\ConversionRate;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification ;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use App\Notifications\UserApprovedNotification;
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
                // TextColumn::make('type'),
                TextColumn::make('user.userPerformance.total_reward')
                    ->label('Poin Dimiliki')
                    ->color('success')
                    ->formatStateUsing(function ($state, $record) {
                        $conversionRate = ConversionRate::value('conversion_rate') ?? 1; // Default ke 1 untuk mencegah error
                        $amountPoints = $record->amount ? round($record->amount / $conversionRate) : 0; // Hitung poin dari amount
                        return ($state + $amountPoints) . ' Poin';
                    }),

                TextColumn::make('poin_ditarik')
                    ->label('Poin Ditarik')
                    ->color('danger')
                    ->getStateUsing(function ($record) {
                        return $record->getPoinDitarikAttribute($record->amount);
                    }),

                TextColumn::make('amount')
                    ->label('Uang Ditarik')
                    ->color('danger')
                    ->formatStateUsing(function ($state){
                        return 'Rp ' . number_format($state, 0, ',', '.') ?? '-';
                    }),

                    TextColumn::make('in_the_name_of')
                    ->label('Atas Nama')
                    ->formatStateUsing(function ($state){
                        return $state ?? '-';
                    }),

                    TextColumn::make('bank_account')
                    ->label('Metode')
                    ->formatStateUsing(function ($state){
                        return $state ?? '-';
                    }),

                    TextColumn::make('no_bank_account')
                    ->label('Nomer Rekening')
                    ->formatStateUsing(function ($state){
                        return $state ?? '-';
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
                    Tables\Actions\Action::make('transfer_proof')
                    ->label('Approve')
                        ->icon('heroicon-o-check')
                        ->color('success')
                    ->form([
                        FileUpload::make('transfer_proof')
                        ->label('Transfer Proof')
                        ->required()
                        ->image()
                        ->visibility('public')
                        ->disk('r2')
                        ->directory(fn (Transaction $record) => 'pasukan/withdraw' . $record->user_id . '/')
                        
                    ])
                    ->action(function (Transaction $record, array $data) {
                    try {
                        $record->update([
                            'transfer_proof' => $data['transfer_proof'],
                            'status' => 'approved', 
                        ]);
                        
                            $record->user->notify(new UserApprovedNotification(
                                'Withdraw Approved',
                                'Your withdraw has been approved',
                                '/dashboard'
                            ));
                        

                        Notification::make()
                        ->title('Approve Successful')
                        ->success()
                        ->send();

                        $userId = $record->user_id;
                                
                                if ($userId) {
                                    DB::table('notifications')->insert([
                                        'id' => (string) Str::uuid(),
                                        'type' => 'Withdraw Approved',
                                        'notifiable_id' => $userId,
                                        'notifiable_type' => User::class,
                                        'data' => json_encode([
                                            'message'  => 'Your withdraw has been approved',
                                            'transaction_id' => $record->id,
                                            
                                        ]),
                                        'read_at' => null,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                        ]);
                                }
                    } catch (\Throwable $th) {
                        $record->user->notify(new UserApprovedNotification(
                            'Withdraw Approved Failed',
                            'Your withdraw has been rejected',
                            '/dashboard'
                        ));
                        Notification::make()
                        ->title('Approve Failed')
                        ->danger()
                        ->send();
                    }
                }),
                    
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->form([
                            TextInput::make('description')
                            ->label('Alasan')
                            ->required()
                            ->minLength(5)
                        ])
                        ->action(function ( Transaction $record, Array $data) {
                        
                            try {
                                $record->update([
                                    'description' => $data['description'],
                                    'status' => 'rejected'
                                ]);
                                $record->user->notify(new UserApprovedNotification(
                                    'Withdraw Rejected',
                                    'Your withdraw has been rejected',
                                    '/dashboard'
                                ));
                                Notification::make()
                                ->title('Successfully rejected')
                                ->success()
                                ->send();

                                $userId = $record->user_id;
                                
                                if ($userId) {
                                    DB::table('notifications')->insert([
                                        'id' => (string) Str::uuid(),
                                        'type' => 'Withdraw Rejected',
                                        'notifiable_id' => $userId,
                                        'notifiable_type' => User::class,
                                        'data' => json_encode([
                                            'message'  => 'Your withdraw has been rejeted',
                                            'transaction_id' => $record->id,
                                            'description' => $record->description
                                        ]),
                                        'read_at' => null,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                        ]);
                                }

                            } catch (\Throwable $th) {
                                Notification::make()
                                ->title(title: 'Withdraw Rejected Failed')
                                ->danger()
                                ->send();
                            }

                        }),
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\Action::make('approve')
            //             ->label('Approve')
            //             ->icon('heroicon-o-check')
            //             ->color('success')
            //             ->requiresConfirmation()
            //             ->action(function ( $record) {
            //                 $record->update(['status' => 'approved']);
            //             }),
            //         Tables\Actions\Action::make('reject')
            //             ->label('Reject')
            //             ->icon('heroicon-o-x-mark')
            //             ->color('danger')
            //             ->requiresConfirmation()
            //             ->action(function ( $record) {
            //                 $record->update(['status' => 'rejected']);
            //             }),
            //     ]),
            // ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(2)
            ->schema([
                TextEntry::make('user.name'),
                TextEntry::make('amount'),
                TextEntry::make('in_the_name_of'),
                TextEntry::make('bank_account'),
                TextEntry::make('no_bank_account'),
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
