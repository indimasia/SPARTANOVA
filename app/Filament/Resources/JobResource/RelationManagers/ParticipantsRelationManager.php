<?php

namespace App\Filament\Resources\JobResource\RelationManagers;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PackageRate;
use Illuminate\Support\Str;
use App\Enums\JobStatusEnum;
use App\Models\UserPerformance;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';
    protected static ?string $title = 'Pasukan';


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($record) => $record->status === JobStatusEnum::APPLIED->value ? 'warning' : ($record->status === JobStatusEnum::APPROVED->value  ? 'success' : ($record->status === JobStatusEnum::REJECTED->value ? 'danger' : 'info'))),
                Tables\Columns\TextColumn::make('reward'),
                Tables\Columns\ImageColumn::make('attachment'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('in_review')
                        ->label('In Review')
                        ->icon('heroicon-o-clock')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            try {
                                $record->update(['status' => JobStatusEnum::IN_REVIEW->value]);
                                Notification::make()
                                    ->title('Successfully in review')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Failed to in review')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->visible(fn ($record) => $record->status === 'pending' || $record->status === 'rejected'|| $record->status === 'approved'),
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            try {
                                $packageRate = PackageRate::where('type', $record->job->type)->pluck('reward')->first();

                                $userPerformance = UserPerformance::firstOrNew(['user_id' => $record->user_id]);
                                $userPerformance->user_id = $record->user_id;
                                $userPerformance->job_completed += 1;
                                $userPerformance->total_reward += $packageRate;
                                $userPerformance->save();

                                \App\Models\Notification::create([
                                    'id' => (string) Str::uuid(),
                                    'type' => 'Job Approved',
                                    'notifiable_id' => $record->user_id,
                                    'notifiable_type' => User::class,
                                    'data' => json_encode([
                                        'message' => 'Your job has been approved!',
                                        'job_id' => $record->id,
                                    ]),
                                    'read_at' => null,
                                ]);

                                    $record->update(['status' => JobStatusEnum::APPROVED->value]);
                                    Notification::make()
                                    ->title('Successfully approved')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Failed to approve')
                                    ->danger()
                                    ->send();
                            }
                        })
                        ->visible(fn ($record) => $record->status===JobStatusEnum::REJECTED->value ||$record->status === JobStatusEnum::REPORTED->value ),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            try {
                                $record->update(['status' => JobStatusEnum::REJECTED->value]);
                                // TODO: update data user performance if rejected
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
                        })
                        ->visible(fn ( $record) => $record->status === 'pending' || $record->status === JobStatusEnum::REPORTED->value || $record->status === JobStatusEnum::APPROVED->value),
                ])->label('Action'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve_all')
                        ->label('Approve All')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ( $records) {
                            try {
                                $records->each->update(['status' => JobStatusEnum::APPROVED->value]);
                                $records->each(function ($record) {
                                    $packageRate = PackageRate::where('type', $record->job->type)->pluck('reward')->first();

                                    $userPerformance = UserPerformance::firstOrNew(['user_id' => $record->user_id]);
                                    $userPerformance->user_id = $record->user_id;
                                    $userPerformance->job_completed += 1;
                                    $userPerformance->total_reward += $packageRate;
                                    $userPerformance->save();
                                });
                                Notification::make()
                                    ->title('Successfully approved')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Failed to approve')
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Tables\Actions\BulkAction::make('reject_all')
                        ->label('Reject All')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ( $records) {
                            try {
                                $records->each->update(['status' => JobStatusEnum::REJECTED->value]);
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
                ]),
            ]);
    }
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
             TextEntry::make('user.name')
                    ->label('Name')
                    ->columnSpanFull(),
                TextEntry::make('user.location')
                    ->default(fn($record) => User::getUserLocation($record->user->latitude, $record->user->longitude)['display_name'] ?? 'Lokasi')
                    ->label('Lokasi')
                    ->columnSpanFull(),
                ImageEntry::make('attachment')
                    ->disk('public')
                    ->default('https://placehold.co/600x400?text=Tidak+Ada+Bukti')
                    ->extraImgAttributes([
                        'alt' => 'Foto Bukti Pengerjaan',
                        'loading' => 'lazy',
                    ])
                    ->label('Bukti Pengerjaan')
                    ->columnSpanFull(),
            ]);
    }
}

