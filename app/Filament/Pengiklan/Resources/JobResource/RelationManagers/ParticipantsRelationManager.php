<?php

namespace App\Filament\Pengiklan\Resources\JobResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use App\Enums\JobStatusEnum;


class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';
    protected static ?string $title = 'Peserta';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Nama Peserta'),
                Forms\Components\Select::make('status')
                    ->options(JobStatusEnum::options())
                    ->default('pending')
                    ->required()
                    ->label('Status'),
                Forms\Components\TextInput::make('reward')
                    ->required()
                    ->numeric()
                    ->default(fn ($record) => $this->getOwnerRecord()->reward)
                    ->readOnly()
                    ->label('Reward'),
                Forms\Components\FileUpload::make('attachment')
                    ->label('Attachment')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->label('Bukti Pengerjaan'),
            ]);

        }

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
                        ->visible(fn ($record) => $record->status === 'pending' || JobStatusEnum::REJECTED->value),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ( $record) {
                            try {
                                $record->update(['status' => JobStatusEnum::REJECTED->value]);
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
                        ->visible(fn ( $record) => $record->status === 'pending' || $record->status === JobStatusEnum::APPROVED->value),
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
                ]),
            ]);
    }

}
