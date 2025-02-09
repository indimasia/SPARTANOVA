<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\GenEnum;
use App\Enums\JobType;
use App\Models\Regency;
use App\Models\Village;
use Filament\Forms\Get;
use Filament\Infolists;
use App\Models\District;
use App\Models\Province;

use Filament\Forms\Form;
use App\Enums\PackageEnum;
use Filament\Tables\Table;
use App\Enums\PlatformEnum;
use App\Models\JobCampaign;
use App\Models\PackageRate;
use App\Enums\UserInterestEnum;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\URL;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JobInAdminResource\Pages;
use App\Filament\Resources\JobInAdminResource\RelationManagers;
use App\Filament\Resources\JobResource\RelationManagers\ParticipantsRelationManager;

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
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => match (true) {
                        is_null($record->is_verified) => 'Belum Verifikasi',
                        $record->is_verified === false => 'Verifikasi Ditolak',
                        default => $record->status, // Gunakan status asli jika sudah diverifikasi
                    })
                    ->icon(fn ($record) => match (true) {
                        is_null($record->is_verified) => 'heroicon-o-question-mark-circle',
                        $record->is_verified === false => 'heroicon-o-x-circle',
                        $record->status === 'publish' => 'heroicon-o-check-circle',
                        $record->status === 'draft' => 'heroicon-o-exclamation-circle',
                        default => null,
                    })
                    ->color(fn ($record) => match (true) {
                        is_null($record->is_verified) => 'gray',
                        $record->is_verified === false => 'danger',
                        $record->status === 'publish' => 'success',
                        $record->status === 'draft' => 'warning',
                        default => 'gray',
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
                        ->hidden(fn (JobCampaign $record): bool => $record->status !== 'draft' || $record->is_verified === null)
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
                        ->hidden(fn (JobCampaign $record): bool => $record->status !== 'publish' || $record->is_verified === null)
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
                    Tables\Actions\Action::make('verify')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->hidden(fn (JobCampaign $record): bool => $record->is_verified === 1)
                        ->action(function (JobCampaign $record) {
                            $record->update(['is_verified' => true]);
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Tolak Verifikasi')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->hidden(fn (JobCampaign $record): bool => $record->is_verified === 0)
                        ->action(function (JobCampaign $record) {
                            $record->update(['is_verified' => false]);
                            Notification::make()
                                ->title('Berhasil Tolak Verifikasi')
                                ->success()
                                ->send();
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
                                Infolists\Components\ImageEntry::make('jobDetail.image')->disk('r2')->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')->size(50)->getStateUsing(fn ($record) => $record->jobDetail->image 
                    ? URL::route('storage.fetch', ['filename' => $record->jobDetail->image]) 
                    : null),
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
