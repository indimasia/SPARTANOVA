<?php

namespace App\Filament\Pengiklan\Resources;

use App\Filament\Pengiklan\Resources\JobResource\Pages;
use App\Filament\Pengiklan\Resources\JobResource\RelationManagers;
use App\Models\JobCampaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use App\Enums\JobType;
use App\Enums\PackageEnum;
use App\Enums\PlatformEnum;
use App\Models\District;
use App\Models\PackageRate;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class JobResource extends Resource
{
    protected static ?string $model = JobCampaign::class;

    protected static ?string $navigationGroup = 'Atur Pekerjaan';
    protected static ?string $navigationLabel = 'Pekerjaan';
    protected static ?int $navigationSort = 1;
    // protected static ?string $recordTitleAttribute = 'Job Campaign';
    protected static ?string $pluralModelLabel = 'Pekerjaan';
    protected static ?string $pluralLabel = 'Pekerjaan';
    protected static ?string $modelLabel = 'Pekerjaan';
    protected static ?string $label = 'Pekerjaan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $defaultView = 'view';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([
                    Wizard\Step::make('Target Pasukan')
                    ->schema([

                        Forms\Components\Section::make('Spesifikasi Akun Pasukan')
                            ->description('Setiap Penambahan Sepesifikasi Akun Pasukan dikenakan biaya tambahan 10%')
                            ->aside()
                            ->schema([
                                Forms\Components\Toggle::make('specific_gender')
                                    ->label('Gender Pasukan')
                                    ->live()
                                    ->validationMessages([
                                        'required' => 'Gender Pasukan Harus Diisi',
                                ]),
                                Forms\Components\ToggleButtons::make('gender')
                                    ->options([
                                        'S' => 'Semua',
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->colors([
                                        'S' => 'success',
                                        'L' => 'info',
                                        'P' => 'danger',
                                    ])
                                    ->label('Gender Pasukan')
                                    ->required()
                                    ->inline()
                                    ->visible(fn(Get $get)=>$get('specific_gender'))
                                    ->validationMessages([
                                        'required' => 'Gender Pasukan Harus Diisi',
                                ]),
                                Forms\Components\Toggle::make('specific_location')
                                    ->label('Lokasi Pasukan')
                                    ->live()
                                    ->validationMessages([
                                        'required' => 'Lokasi Pasukan Harus Diisi',
                                ]),
                                Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('province_kode')
                                    ->label('Provinsi')
                                    ->options(Province::getAvailableWarriorInProvince())
                                    ->multiple()
                                    ->placeholder('pilih wilayah yang ditargetkan"')
                                    ->preload()
                                    ->searchable()
                                    ->required(),
                                    Forms\Components\Select::make('regency_kode')
                                        ->label('Kabupaten/Kota')
                                        ->placeholder('pilih wilayah yang ditargetkan"')
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->options(function(Get $get){
                                            return Regency::getAvailableWarriorInRegency($get('province_kode'));
                                        })
                                        ->live()
                                        ->required(),
                                    Forms\Components\Select::make('district_kode')
                                        ->label('Kecamatan')
                                        ->placeholder('pilih wilayah yang ditargetkan"')
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->options(function(Get $get){
                                            return District::getAvailableWarriorInDistrict($get('regency_kode'));
                                        })
                                        ->live()
                                        ->required(),
                                    Forms\Components\Select::make('village_kode')
                                        ->label('Kelurahan')
                                        ->placeholder('pilih wilayah yang ditargetkan"')
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->options(function(Get $get){
                                            return Village::getAvailableWarriorInVillage($get('district_kode'));
                                        })
                                        ->live()
                                        ->required(),
                                 ])->visible(fn(Get $get)=>$get('specific_location')),
                                Forms\Components\Toggle::make('specific_interest')
                                    ->label('Interest Pasukan')
                                    ->live()
                                    ->validationMessages([
                                        'required' => 'Interest Pasukan Harus Diisi',
                                ]),

                                Forms\Components\ToggleButtons::make('interest')
                                    ->visible(fn(Get $get)=>$get('specific_interest'))
                                    ->label('Interest Pasukan')
                                    ->options([
                                       'Travel',
                                       'Food',
                                       'Fashion',
                                       'Beauty',
                                       'Technology',
                                       'Health',
                                       'Sports',
                                       'Music',
                                       'Art',
                                       'Gaming',
                                       'Movies',
                                       'Books',
                                       'Technology',
                                       'Science',
                                       'History',
                                       'Travel',
                                    ])
                                    ->required()
                                    ->inline()
                                    ->multiple()
                                    ->validationMessages([
                                        'required' => 'Interest Pasukan Harus Diisi',
                                ]),

                                ]),
                    ]),
                    Wizard\Step::make('Pekerjaan')
                        ->schema([
                            Forms\Components\Select::make('type')
                                ->options(JobType::options())
                                ->label('Tipe Pekerjaan')
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    $set('package_rate', null);
                                })
                                // ->inline()
                                ->required()
                                ->validationMessages([
                                    'required' => 'Tipe Pekerjaan Harus Diisi',
                                ]),
                            Forms\Components\ToggleButtons::make('platform')
                                ->options(PlatformEnum::options())
                                // ->searchable()
                                ->label('Social Media')
                                ->required()
                                ->inline()
                                ->helperText(fn(Get $get) => $get('type') == JobType::POSTING->value ? 'Video dan caption  yang diposting tidak boleh menjatuhkan orang/produk lain.' : '')
                                ->validationMessages([
                                    'required' => 'Social Media Harus Diisi',
                                ]),
                            Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('package_rate')
                                ->options(function(Get $get){
                                    return PackageRate::packageList($get('type'));
                                })
                                ->live()
                                ->searchable()
                                // ->afterStateUpdated(function (callable $set, $state) {
                                //     $selectedPackage = PackageRate::find($state);
                                //     if ($selectedPackage) {
                                //         $set('reward', $selectedPackage->price);
                                //     } else {
                                //         $set('reward', null);
                                //     }
                                // })
                                ->label('Paket')
                                ->required()
                                    ->validationMessages([
                                        'required' => 'Paket Harus Diisi',
                                    ]),
                                    Forms\Components\TextInput::make('quota')
                                        ->visible(fn(Get $get)=>$get('package_rate') == PackageEnum::LAINNYA->value && $get('package_rate') != '')
                                        ->numeric()
                                        ->minValue(10001)
                                        ->label('Kuota')
                                        ->required()
                                        ->validationMessages([
                                            'required' => 'Kuota Harus Diisi',
                                            'min' => 'Kuota Harus Lebih Besar Dari 10000',
                                        ]),
                                    Forms\Components\TextInput::make('reward')
                                        ->required()
                                        ->numeric()
                                        ->label('Hadiah')
                                        ->validationMessages([
                                            'required' => 'Hadiah Harus Diisi',
                                        ]),
                                    ]),
                            Grid::make(2)
                                ->schema([
                                    Forms\Components\DatePicker::make('start_date')
                                        ->live()
                                        ->reactive()
                                        ->required()
                                        ->label('Tanggal Mulai')
                                        ->afterStateUpdated(fn(callable $set, $state)=>$set('end_date',null))
                                        ->validationMessages([
                                            'required' => 'Tanggal Mulai Harus Diisi',
                                        ]),
                                    Forms\Components\DatePicker::make('end_date')
                                        ->live()
                                        ->reactive()
                                        ->minDate(fn(Get $get)=>$get('start_date'))
                                        ->required()
                                        ->rules(['after_or_equal:start_date'])
                                        ->label('Tanggal Selesai')
                                        ->validationMessages([
                                            'required' => 'Tanggal Selesai Harus Diisi',
                                            'after_or_equal' => 'Tanggal Selesai Harus Setelah Tanggal Mulai',
                                        ])
                                        ,
                                        Forms\Components\ToggleButtons::make('status')
                                            ->options([
                                                'publish' => 'Publikasi',
                                                'draft' => 'Draft',
                                            ])

                                            ->colors([
                                                'publish' => 'success',
                                                'draft' => 'warning',
                                            ])
                                            ->icons([
                                                'publish' => 'heroicon-o-check-circle',
                                                'draft' => 'heroicon-o-exclamation-circle',
                                            ])
                                            ->required()
                                            ->inline()
                                            ->validationMessages([
                                                'required' => 'Status Harus Diisi',
                                            ]),
                                        Forms\Components\Toggle::make('is_multiple')
                                            ->label('Dapat Diikuti Berulang')
                                            ->required(),
                                ]),

                        ]),

                    Wizard\Step::make('Detail Pekerjaan')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Pekerjaan')
                            ->validationMessages([
                                'required' => 'Nama Pekerjaan Harus Diisi',
                            ])
                            ,
                            Forms\Components\FileUpload::make('jobDetail.image')
                                ->label('Gambar')
                                ->required()
                                ->image()
                                ->disk('public')
                                ->imageEditor()
                                ->imageCropAspectRatio('16:9')
                                ->imageResizeMode('cover')
                                ->imageResizeTargetWidth('1024')
                                ->imageResizeTargetHeight('576')
                                ->validationMessages([
                                    'required' => 'Gambar Harus Diisi',
                                ])
                                ,
                            Forms\Components\TextInput::make('jobDetail.description')
                                ->label('Deskripsi')
                                ->required()
                                ->maxLength(255)
                                ->validationMessages([
                                    'required' => 'Deskripsi Harus Diisi',
                                ])
                                ,
                            Forms\Components\RichEditor::make('instructions')
                                ->toolbarButtons([
                                    'bulletList',
                                    'orderedList',
                                    'redo',
                                    'undo',
                                ])
                                ->required()
                                ->validationMessages([
                                    'required' => 'Instruksi Harus Diisi',
                                ])
                                ,
                            Forms\Components\TextInput::make('jobDetail.url_link')
                                ->label('Link')
                                ->required()
                                ->url()
                                ->maxLength(255)
                                ->validationMessages([
                                    'required' => 'Link Harus Diisi',
                                ])
                                ,
                        ]),
                    Wizard\Step::make('Tinjauan')
                        ->schema([
                            Forms\Components\Placeholder::make('title')
                            ->content(fn(Get $get)=>$get('title'))
                            ->label('Nama Pekerjaan')
                            ->columnSpanFull()
                        ]),
                ])->columnSpanFull()
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="md"
                        >
                            Simpan
                        </x-filament::button>
                    BLADE)))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('participant_count')->label('Jumlah Peserta'),
                Tables\Columns\TextColumn::make('type')->label('Tipe Pekerjaan'),
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
            ])
            ->filters([
                //
            ])
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
                Infolists\Components\Section::make('Pekerjaan')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Grid::make(2)
                                ->schema([
                                    Infolists\Components\Group::make([
                                        Infolists\Components\TextEntry::make('title')
                                        ->label('Nama Pekerjaan'),
                                        Infolists\Components\TextEntry::make('type')
                                        ->label('Tipe Pekerjaan'),
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
                Infolists\Components\Section::make('Detail Pekerjaan')
                    ->schema([
                        Infolists\Components\TextEntry::make('jobDetail.description')
                            ->label('Deskripsi')
                            ->prose()
                            ->markdown(),
                            Infolists\Components\TextEntry::make('instructions')
                            ->label('Instruksi')
                            ->prose()
                            ->markdown(),
                    ])

                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ParticipantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'view' => Pages\ViewJob::route('/{record}'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
