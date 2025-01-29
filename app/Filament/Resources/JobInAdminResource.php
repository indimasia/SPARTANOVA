<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobInAdminResource\Pages;
use App\Filament\Resources\JobInAdminResource\RelationManagers;
use App\Models\JobCampaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Enums\GenEnum;
use App\Enums\JobType;
use App\Models\Regency;
use App\Models\Village;
use Filament\Forms\Get;
use Filament\Infolists;
use App\Models\District;
use App\Models\Province;
use App\Enums\PackageEnum;
use App\Enums\PlatformEnum;
use App\Models\PackageRate;
use App\Enums\UserInterestEnum;
use App\Filament\Resources\JobResource\RelationManagers\ParticipantsRelationManager;
use Filament\Infolists\Infolist;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Forms\Components\ToggleButtons;

class JobInAdminResource extends Resource
{
    protected static ?string $model = JobCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Semua Misi';
    protected static ?string $navigationGroup = 'Atur Misi';
    protected static ?int $navigationSort = 1;
    protected static ?string $defaultView = 'view';
    protected static ?string $pluralModelLabel = 'Semua Misi';
    protected static ?string $pluralLabel = 'Semua Misi';
    protected static ?string $modelLabel = 'Semua Misi';
    protected static ?string $label = 'Semua Misi';


    public static function getNavigationBadge(): ?string
    {
        return JobCampaign::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('participant_count')->label('Jumlah Peserta'),
                Tables\Columns\TextColumn::make('type')->label('Tipe Misi'),
                Tables\Columns\TextColumn::make('platform')
                    ->label('Social Media')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('quota')->label('Kuota'),
                Tables\Columns\TextColumn::make('reward')->label('Hadiah'),
                Tables\Columns\TextColumn::make('status')->badge()->icon(fn ($state) => match ($state) {
                    'publish' => 'heroicon-o-check-circle',
                    'draft' => 'heroicon-o-exclamation-circle',
                })
                ->color(fn ($state) => match ($state) {
                    'publish' => 'success',
                    'draft' => 'warning',
                }),
                Tables\Columns\TextColumn::make('start_date')->date()->label('Tanggal Mulai')->toggleable(),
                Tables\Columns\TextColumn::make('end_date')->date()->label('Tanggal Selesai')->toggleable(),
                Tables\Columns\TextColumn::make('createdBy.name')->label('Dibuat Oleh')->toggleable(),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('publish')
                        ->label('Publikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->hidden(fn (JobCampaign $record): bool => $record->status !== 'draft')
                        ->action(function (JobCampaign $record) {
                            try {
                                $record->update(['status' => 'publish']);
                                Notification::make()
                                    ->title('Berhasil Publikasi')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Gagal Publikasi')
                                    ->danger()
                                    ->send();
                            }
                        }),
                        Tables\Actions\Action::make('draft')
                        ->label('Draft')
                        ->icon('heroicon-o-exclamation-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->hidden(fn (JobCampaign $record): bool => $record->status !== 'publish')
                        ->action(function (JobCampaign $record) {
                            try {
                                $record->update(['status' => 'draft']);
                                Notification::make()
                                    ->title('Berhasil Draft')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Gagal Draft')
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            try {
                                $records->each->update(['status' => 'publish']);
                                Notification::make()
                                    ->title('Berhasil Publikasi')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Gagal Publikasi')
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Tables\Actions\BulkAction::make('draft')
                        ->label('Draft')
                        ->icon('heroicon-o-exclamation-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            try {
                                $records->each->update(['status' => 'draft']);
                                Notification::make()
                                    ->title('Berhasil Draft')
                                    ->success()
                                    ->send();
                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Gagal Draft')
                                    ->danger()
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Misi')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Grid::make(2)
                                ->schema([
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('title')
                                        ->label('Nama Misi'),
                                        Infolists\Components\TextEntry::make('type')
                                        ->label('Tipe Misi'),
                                        Infolists\Components\TextEntry::make('platform')
                                        ->label('Social Media'),
                                        Infolists\Components\IconEntry::make('is_multiple')
                                        ->label('Dapat Diikuti Berulang')
                                        ->icon(fn (string $state): string => match ($state) {
                                            '1' => 'heroicon-o-check-circle',
                                            '0' => 'heroicon-o-x-circle',
                                        })
                                        ->color(fn (string $state): string => match ($state) {
                                            '1' => 'success',
                                            '0' => 'danger',
                                        }),

                                    ]),
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('participant_count')
                                            ->label('Jumlah Peserta')
                                            ->getStateUsing(fn ($record) => $record->getParticipantCountAttribute() . ' / ' . $record->quota),
                                        Infolists\Components\TextEntry::make('reward'),
                                            // ->icon('heroicon-o-cash')
                                        Infolists\Components\TextEntry::make('status')->badge()->color(fn ($state) => match ($state) {
                                            'publish' => 'success',
                                            'draft' => 'warning',
                                        }),
                                        Infolists\Components\TextEntry::make('createdBy.name')->label('Dibuat Oleh'),

                                        ]),
                                ]),

                            Infolists\Components\Group::make([
                                Infolists\Components\ImageEntry::make('jobDetail.image')
                                    ->hiddenLabel()
                                    ->grow(false),
                                Infolists\Components\TextEntry::make('start_date')->label('Tanggal Mulai'),
                                Infolists\Components\TextEntry::make('end_date')->label('Tanggal Selesai'),
                            ])->grow(false),
                        ])->from('lg'),
                    ]),
                Infolists\Components\Section::make('Detail Misi')
                    ->schema([
                        Infolists\Components\TextEntry::make('jobDetail.description')
                            ->label('Deskripsi')
                            ->prose()
                            ->markdown(),
                            Infolists\Components\TextEntry::make('instructions')
                            ->label('Instruksi')
                            ->prose()
                            ->markdown(),
                            Infolists\Components\TextEntry::make('jobDetail.caption')
                            ->label('Caption')
                            ->prose()
                            ->markdown(),
                    ])
                    ->collapsible(),
                Infolists\Components\Section::make('Target Pasukan')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                                ->schema([
                                    Infolists\Components\TextEntry::make('jobDetail.specific_gender')->label('Gender'),
                                    Infolists\Components\TextEntry::make('jobDetail.specific_generation')->label('Generasi')->default('-'),
                                    Infolists\Components\TextEntry::make('jobDetail.specific_province')
                                        ->label('Provinsi')
                                        ->getStateUsing(fn($record) => !empty($record->jobDetail->specific_province) ? Province::getProvinceName($record->jobDetail->specific_province) : '-')
                                        ->badge(),
                                    Infolists\Components\TextEntry::make('jobDetail.specific_regency')
                                        ->label('Kabupaten/Kota')
                                        ->getStateUsing(fn($record) => !empty($record->jobDetail->specific_regency) ? Regency::getRegencyName($record->jobDetail->specific_regency) : '-')
                                        ->badge(),
                                    Infolists\Components\TextEntry::make('jobDetail.specific_district')
                                        ->label('Kecamatan')
                                        ->getStateUsing(fn($record) => !empty($record->jobDetail->specific_district) ? District::getDistrictName($record->jobDetail->specific_district) : '-')
                                        ->badge(),
                                    Infolists\Components\TextEntry::make('jobDetail.specific_village')
                                        ->label('Kelurahan')
                                        ->getStateUsing(fn($record) => !empty($record->jobDetail->specific_village) ? Village::getVillageName($record->jobDetail->specific_village) : '-')
                                        ->badge(),
                                    Infolists\Components\TextEntry::make('jobDetail.specific_interest')->label('Interest')->default('-')->badge(),
                                ])
                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ParticipantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobInAdmins::route('/'),
            'create' => Pages\CreateJobInAdmin::route('/create'),
            'edit' => Pages\EditJobInAdmin::route('/{record}/edit'),
            'view' => Pages\jobDetail::route('/{record}'),
        ];
    }
}
