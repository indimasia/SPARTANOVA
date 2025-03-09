<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Regency;
use App\Models\Village;
use Filament\Forms\Get;
use App\Models\District;
use Filament\Forms\Form;
use App\Mail\MyTestEmail;
use Filament\Tables\Table;
use App\Enums\UserStatusEnum;
use Filament\Resources\Resource;
use App\Models\SosialMediaAccount;
use Filament\Support\Colors\Color;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use App\Notifications\UserApprovedNotification;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Jobs\ProcessSendEmail;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Bagian Pengguna';
    protected static ?string $navigationLabel = 'Semua Pengguna';
    protected static ?string $pluralModelLabel = 'Semua Pengguna';
    protected static ?string $modelLabel = 'Semua Pengguna';
    protected static ?string $slug = 'pengguna';
    protected static ?int $navigationSort = 0;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($context) => $context === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->placeholder(function (string $operation) {
                        if ($operation === 'edit') {
                            return 'Isi untuk mengubah password';
                        }
                    })
                    ->hint(function (string $operation) {
                        if ($operation === 'edit') {
                            return 'Biarkan kosong jika tidak ingin mengubah password';
                        }
                    })
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->options([
                        'L' => 'Laki-Laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('province_kode')
                    ->relationship('province', 'nama')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('regency_kode')
                    ->relationship('regency', 'nama')
                    ->preload()
                    ->searchable()
                    ->options(function(Get $get){
                        return Regency::where('prov_kode', $get('province_kode'))->whereNotNull(['nama','kode'])
                            ->pluck('nama', 'kode');
                    })
                    ->live()
                    ->required(),
                Forms\Components\Select::make('district_kode')
                    ->relationship('district', 'nama')
                    ->preload()
                    ->searchable()
                    ->options(function(Get $get){
                        return District::where('regency_kode', $get('regency_kode'))->whereNotNull(['nama','kode'])
                            ->pluck('nama', 'kode');
                    })
                    ->live()
                    ->required(),
                Forms\Components\Select::make('village_kode')
                    ->relationship('village', 'nama')
                    ->preload()
                    ->searchable()
                    ->options(function(Get $get){
                        return Village::where('district_kode', $get('district_kode'))->whereNotNull(['nama','kode'])
                            ->pluck('nama', 'kode');
                    })
                    ->live()
                    ->required(),
                    Forms\Components\TextInput::make('instagram')
                    ->label('Instagram')
                    ->maxLength(225)
                    ->hint('Kosongkan jika tidak memiliki akun Instagram'),
                    Forms\Components\TextInput::make('tiktok')
                    ->label('Tiktok')
                    ->maxLength(225)
                    ->hint('Kosongkan jika tidak memiliki akun Toktok'),
                    Forms\Components\TextInput::make('youtube')
                    ->label('Youtube')
                    ->maxLength(225)
                    ->hint('Kosongkan jika tidak memiliki akun Youtube'),
                    Forms\Components\TextInput::make('facebook')
                    ->label('Facebook')
                    ->maxLength(225)
                    ->hint('Kosongkan jika tidak memiliki akun Facebook'),
                    Forms\Components\TextInput::make('twitter')
                    ->label('Twitter')
                    ->maxLength(225)
                    ->hint('Kosongkan jika tidak memiliki akun Twitter'),
                    Forms\Components\TextInput::make('google')
                    ->label('Goggle')
                    ->maxLength(225)
                    ->hint('Kosongkan jika tidak memiliki akun Goggle'),
                    Forms\Components\TextInput::make('whatsapp')
                    ->label('WhatsApp')
                    ->tel()
                    ->telRegex('/^(\+62|62|0)8[1-9][0-9]{6,10}$|^\+?[1-9][0-9\s\-\(\)]{6,20}$/')
                    ->hint('Kosongkan jika tidak memiliki akun Goggle'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('province.nama')
                ->toggleable(),
                Tables\Columns\TextColumn::make('regency.nama')
                ->toggleable(),
                Tables\Columns\TextColumn::make('district.nama')
                ->toggleable(),
                Tables\Columns\TextColumn::make('village.nama')
                ->toggleable(),
                Tables\Columns\TextColumn::make('roles.name')
                ->badge()
                ->color(fn($record) => $record->roles->contains('name', 'admin') ? 'warning' : ($record->roles->contains('name', 'pengiklan') ? 'success' : 'info')),
                Tables\Columns\TextColumn::make('userPerformance.total_reward')
                ->label('Saldo')
                ->default(0)
                ->formatStateUsing(fn($record) => $record->userPerformance->total_reward ?? '0'),
                Tables\Columns\TextColumn::make('gender')
                ->state(fn($record) => $record->gender == 'L' ? 'Laki-Laki' : 'Perempuan')
                ->color(fn($record) => $record->gender == 'L' ? 'warning' : 'danger'),

                Tables\Columns\TextColumn::make('date_of_birth')->date(),
                Tables\Columns\TextColumn::make('Instagram')
    ->label('Instagram')
    ->getStateUsing(fn($record) => $record->getSocialMediaUsername('Instagram')),

Tables\Columns\TextColumn::make('Tiktok')
    ->label('Tiktok')
    ->getStateUsing(fn($record) => $record->getSocialMediaUsername('TikTok')),

Tables\Columns\TextColumn::make('Youtube')
    ->label('Youtube')
    ->getStateUsing(fn($record) => $record->getSocialMediaUsername('Youtube')),

Tables\Columns\TextColumn::make('Facebook')
    ->label('Facebook')
    ->getStateUsing(fn($record) => $record->getSocialMediaUsername('Facebook')),

Tables\Columns\TextColumn::make('Twitter')
    ->label('Twitter')
    ->getStateUsing(fn($record) => $record->getSocialMediaUsername('Twitter')),

Tables\Columns\TextColumn::make('Google')
    ->label('Google')
    ->getStateUsing(fn($record) => $record->getSocialMediaUsername('Google')),

Tables\Columns\TextColumn::make('Whatsapp')
    ->label('Whatsapp')
    ->getStateUsing(fn($record) => $record->getSocialMediaUsername('WhatsApp')),



                Tables\Columns\TextColumn::make('status')
                ->getStateUsing(fn($record) => match($record?->status) {
                    UserStatusEnum::ACTIVE->value => 'aktif',
                    UserStatusEnum::SUSPENDED->value => 'suspended',
                    UserStatusEnum::PENDING->value => 'pending',
                    UserStatusEnum::REJECTED->value => 'rejected',
                    default => 'lainnya'
                })
                ->badge()
                ->color(fn($record) => match($record?->status) {
                    UserStatusEnum::ACTIVE->value => 'success',
                    UserStatusEnum::SUSPENDED->value => 'danger',
                    UserStatusEnum::PENDING->value => 'info',
                    UserStatusEnum::REJECTED->value => 'warning',
                    default => 'secondary'
                }),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->visible(fn ($record) => $record->status == UserStatusEnum::PENDING->value)
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            // dd($record);
                            $record->update(['status' => UserStatusEnum::ACTIVE->value]);
                            $record->notify(new UserApprovedNotification(
                                'Akun Anda Disetujui!',
                                'Selamat, akun Anda telah di-approve oleh admin.',
                                '/dashboard'
                            ));
                            
                            ProcessSendEmail::dispatch($record);
                            
                            Notification::make()
                                ->title('Berhasil')
                                ->body('User berhasil di-approve.')
                                ->success()
                                ->send();

                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->visible(fn ($record) => $record->status == UserStatusEnum::PENDING->value)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['status' => UserStatusEnum::REJECTED->value]);
                            Notification::make()
                                ->title('Berhasil')
                                ->body('User berhasil di-reject.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('suspend')
                        ->label('Suspend')
                        ->icon('heroicon-o-pause-circle')
                        ->visible(fn ($record) => $record->status == UserStatusEnum::ACTIVE->value)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['status' => UserStatusEnum::SUSPENDED->value]);
                            Notification::make()
                                ->title('Berhasil')
                                ->body('User berhasil di-suspend.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\Action::make('unsuspend')
                        ->label('Unsuspend')
                        ->icon('heroicon-o-play-circle')
                        ->visible(fn ($record) => $record->status == UserStatusEnum::SUSPENDED->value)
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update(['status' => UserStatusEnum::ACTIVE->value]);
                            Notification::make()
                                ->title('Berhasil')
                                ->body('User berhasil di-unsuspend.')
                                ->success()
                                ->send();
                        }),
                ])->icon('heroicon-s-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('approve_all')
                        ->label('Approve All')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $updatedCount = 0;
                            $records->each(function ($record) use (&$updatedCount) {
                                if ($record->status != UserStatusEnum::ACTIVE->value) {
                                    $record->update(['status' => UserStatusEnum::ACTIVE->value]);
                                    $updatedCount++;
                                    ProcessSendEmail::dispatch($record);
                                }
                                
                            });

                            Notification::make()
                                ->title('Berhasil')
                                ->body($updatedCount . ' user berhasil di-approve.')
                                ->success()
                                ->send();
                        }),

                    // Bulk Reject
                    Tables\Actions\BulkAction::make('reject_all')
                        ->label('Reject All')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $updatedCount = 0;
                            $records->each(function ($record) use (&$updatedCount) {
                                if ($record->status != UserStatusEnum::REJECTED->value) {
                                    $record->update(['status' => UserStatusEnum::REJECTED->value]);
                                    $updatedCount++;
                                }
                            });

                            Notification::make()
                                ->title('Berhasil')
                                ->body($updatedCount . ' user berhasil di-reject.')
                                ->success()
                                ->send();
                            }),

                    // Bulk Suspend
                    Tables\Actions\BulkAction::make('suspend_all')
                        ->label('Suspend All')
                        ->icon('heroicon-o-pause-circle')
                        ->color(Color::Slate)
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $updatedCount = 0;
                            $records->each(function ($record) use (&$updatedCount) {
                                if ($record->status != UserStatusEnum::SUSPENDED->value) {
                                    $record->update(['status' => UserStatusEnum::SUSPENDED->value]);
                                    $updatedCount++;
                                }
                            });

                            Notification::make()
                                ->title('Berhasil')
                                ->body($updatedCount . ' user berhasil di-suspend.')
                                ->success()
                                ->send();
                            }),

                    // Bulk Unsuspend
                    Tables\Actions\BulkAction::make('unsuspend_all')
                        ->label('Unsuspend All')
                        ->icon('heroicon-o-play-circle')
                        ->color('info')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $updatedCount = 0;
                            $records->each(function ($record) use (&$updatedCount) {
                                if ($record->status != UserStatusEnum::SUSPENDED->value) {
                                    $record->update(['status' => UserStatusEnum::SUSPENDED->value]);
                                    $updatedCount++;
                                }
                            });

                            Notification::make()
                                ->title('Berhasil')
                                ->body($updatedCount . ' user berhasil di-unsuspend.')
                                ->success()
                                ->send();
                            }),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
