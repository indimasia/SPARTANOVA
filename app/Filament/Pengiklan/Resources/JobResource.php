<?php

namespace App\Filament\Pengiklan\Resources;

use Carbon\Carbon;
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
use App\Filament\Pengiklan\Resources\JobResource\Pages;
use App\Filament\Pengiklan\Resources\JobResource\RelationManagers;

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
                    ->icon('heroicon-s-funnel')
                    ->completedIcon('heroicon-s-funnel')
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
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->colors([
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
                                Forms\Components\Toggle::make('specific_generation')
                                    ->label('Generasi Pasukan')
                                    ->live()
                                    ->validationMessages([
                                        'required' => 'Generasi Pasukan Harus Diisi',
                                ]),
                                Forms\Components\ToggleButtons::make('generation')
                                    ->options(GenEnum::options())
                                    ->label('Generasi Pasukan')
                                    ->required()
                                    ->inline()
                                    ->multiple()
                                    ->visible(fn(Get $get)=>$get('specific_generation'))
                                    ->validationMessages([
                                        'required' => 'Generasi Pasukan Harus Diisi',
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
                                    ->placeholder('pilih wilayah yang ditargetkan')
                                    ->preload()
                                    ->searchable()
                                    ->required(),

                                    Forms\Components\ToggleButtons::make('all_regency')
                                    ->boolean()
                                    ->label('Pilih Semua Kabupaten/Kota?')
                                    ->live()
                                    ->default(true)
                                    ->afterStateUpdated(fn(callable $set, $state)=>$set('all_district',true))
                                    ->grouped()
                                    ->required(),
                                    Forms\Components\Select::make('regency_kode')
                                        ->label('Kabupaten/Kota')
                                        ->placeholder('pilih wilayah yang ditargetkan')
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->options(function(Get $get){
                                            return Regency::getAvailableWarriorInRegency($get('province_kode'));
                                        })
                                        ->visible(fn(Get $get)=>$get('all_regency') == false)
                                        ->live()
                                        ->required(),
                                    Forms\Components\ToggleButtons::make('all_district')
                                    ->boolean()
                                    ->label('Pilih Semua Kecamatan?')
                                    ->live()
                                    ->visible(fn(Get $get)=>$get('all_regency') == false)
                                    ->afterStateUpdated(fn(callable $set, $state)=>$set('all_village',true))
                                    ->hidden(fn(Get $get) => $get('all_regency') == true)
                                    ->default(true)
                                    ->grouped()
                                    ->required(),
                                    Forms\Components\Select::make('district_kode')
                                        ->label('Kecamatan')
                                        ->placeholder('pilih wilayah yang ditargetkan')
                                        ->preload()
                                        ->searchable()
                                        ->multiple()
                                        ->options(function(Get $get){
                                            return District::getAvailableWarriorInDistrict($get('regency_kode'));
                                        })
                                        ->visible(fn(Get $get)=>$get('all_district') == false)
                                        ->hidden(fn(Get $get) => $get('all_regency') == true)
                                        ->live()
                                        ->required(),
                                    Forms\Components\ToggleButtons::make('all_village')
                                        ->boolean()
                                        ->label('Pilih Semua Kelurahan?')
                                        ->live()
                                        ->visible(fn(Get $get) => $get('all_district') == false)

                                        ->hidden(fn(Get $get) => $get('all_regency') == true || $get('all_district') == true)
                                        ->default(true)
                                        ->grouped()
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
                                        ->visible(fn(Get $get)=>$get('all_village') == false)
                                        ->hidden(fn(Get $get) => $get('all_regency') == true  || $get('all_district') == true)
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
                                    ->options(UserInterestEnum::options())
                                    ->required()
                                    ->inline()
                                    ->multiple()
                                    ->validationMessages([
                                        'required' => 'Interest Pasukan Harus Diisi',
                                ]),

                                ]),
                    ]),
                    Wizard\Step::make('Pekerjaan')
                    ->icon('heroicon-s-briefcase')
                    ->completedIcon('heroicon-s-briefcase')
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
                    ->icon('heroicon-s-document-text')
                    ->completedIcon('heroicon-s-document-text')
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
                            // ->imageCropAspectRatio('16:9')
                            ->imageResizeMode('cover')
                            // ->imageResizeTargetWidth('1024')
                            // ->imageResizeTargetHeight('576')
                            ->afterStateUpdated(function (callable $set, $state) {
                                if ($state) {
                                    $path = $state->store('images', 'public');
                                    session()->put('temporary_image_path', $path);
                                }
                            })
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
                                ->required()
                                ->toolbarButtons([
                                    'bulletList',
                                    'orderedList',
                                    'redo',
                                    'undo',
                                ])
                                ->validationMessages([
                                    'required' => 'Instruksi Harus Diisi',
                                ])
                                ,
                            Forms\Components\TextInput::make('jobDetail.url_link')
                                ->label('Link')
                                ->required(fn (Get $get) => $get('type') !== JobType::POSTING->value)
                                ->url()
                                ->maxLength(255)
                                ->validationMessages([
                                    'required' => 'Link Harus Diisi',
                                ])
                                ,
                            Forms\Components\TextInput::make('jobDetail.caption')
                                ->label('Caption')
                                ->required(fn (Get $get) => $get('type') == JobType::POSTING->value)
                                ->maxLength(255)
                                ->validationMessages([
                                    'required' => 'Caption Harus Diisi',
                                ])
                                ,
                        ]),
                    Wizard\Step::make('Tinjauan')
                    ->icon('heroicon-s-eye')
                    ->completedIcon('heroicon-s-eye')
                        ->schema([
                            Forms\Components\Section::make('Informasi Pekerjaan')
                                ->icon('heroicon-m-document-text')
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\Placeholder::make('imagePlaceHolder')
                                            ->content(function (Get $get) {
                                                $imagePath = session('temporary_image_path');
                                                
                                                if ($imagePath) {
                                                    $imageUrl = asset('storage/images/' . basename($imagePath));
                                                    
                                                    return new HtmlString('<img src="' . $imageUrl . '" alt="Gambar Pekerjaan" style="max-width: 50%; height: auto; margin: 0 auto;">');
                                                }
                                                
                                                return 'Tidak ada gambar yang diunggah';
                                            })
                                            ->label('Gambar Pekerjaan')
                                            ->columnSpanFull(),

                                            Forms\Components\Placeholder::make('typePlaceHolder')
                                                ->content(fn(Get $get) => $get('type'))
                                                ->label('Tipe Pekerjaan'),
                    
                                            Forms\Components\Placeholder::make('platformPlaceHolder')
                                                ->content(fn(Get $get) => $get('platform'))
                                                ->label('Social Media'),
                    
                                            Forms\Components\Placeholder::make('package_ratePlaceHolder')
                                                ->content(fn(Get $get) => $get('package_rate'))
                                                ->label('Paket'),
                    
                                            Forms\Components\Placeholder::make('rewardPlaceHolder')
                                                ->content(fn(Get $get) => $get('reward'))
                                                ->label('Hadiah'),
                    
                                            Forms\Components\Placeholder::make('start_datePlaceHolder')
                                                ->content(fn(Get $get) => $get('start_date'))
                                                ->label('Tanggal Mulai'),
                    
                                            Forms\Components\Placeholder::make('end_datePlaceHolder')
                                                ->content(fn(Get $get) => $get('end_date'))
                                                ->label('Tanggal Selesai'),
                    
                                            Forms\Components\Placeholder::make('is_multiplePlaceHolder')
                                                ->content(fn(Get $get) => $get('is_multiple') ? 'Ya' : 'Tidak')
                                                ->label('Dapat Diikuti Berulang'),
                    
                                            Forms\Components\Placeholder::make('statusPlaceHolder')
                                                ->content(fn(Get $get) => $get('status'))
                                                ->label('Status'),
                                        ]),
                                    Forms\Components\Placeholder::make('titlePlaceHolder')
                                        ->content(fn(Get $get) => $get('title'))
                                        ->label('Nama Pekerjaan')
                                        ->columnSpanFull(),
                    
                                    Forms\Components\Placeholder::make('descriptionPlaceHolder')
                                        ->content(fn(Get $get) => $get('jobDetail.description'))
                                        ->label('Deskripsi')
                                        ->columnSpanFull(),
                    
                                    Forms\Components\Placeholder::make('instructionsPlaceHolder')
                                        ->content(fn(Get $get) => strip_tags($get('instructions')))
                                        ->label('Instruksi')
                                        ->columnSpanFull(),
                    
                                    Forms\Components\Placeholder::make('captionPlaceHolder')
                                        ->content(fn(Get $get) => $get('jobDetail.caption'))
                                        ->label('Caption')
                                        ->columnSpanFull(),

                                    Forms\Components\Placeholder::make('url_linkPlaceHolder')
                                        ->content(fn(Get $get) => $get('jobDetail.url_link'))
                                        ->label('Link')
                                        ->columnSpanFull(),
                                ])
                                ->collapsible(),
                    
                            Forms\Components\Section::make('Target Pasukan')
                                ->icon('heroicon-m-funnel')
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\Placeholder::make('genderPlaceHolder')
                                                ->content(fn(Get $get) => $get('gender') ?? 'Tidak ada pilihan')
                                                ->label('Gender'),
                    
                                            Forms\Components\Placeholder::make('generationPlaceHolder')
                                                ->content(fn(Get $get) => $get('generation') ? implode(', ', $get('generation')) : 'Tidak ada pilihan')
                                                ->label('Generasi'),
                    
                                            Forms\Components\Placeholder::make('locationPlaceHolder')
                                                ->content(fn(Get $get) => $get('province_kode') ? implode(', ', Province::whereIn('kode', $get('province_kode'))->pluck('nama')->toArray()) : 'Tidak ada pilihan')
                                                ->label('Provinsi'),
                    
                                            Forms\Components\Placeholder::make('regencyPlaceHolder')
                                                ->content(fn(Get $get) => $get('regency_kode') ? implode(', ', Regency::whereIn('kode', $get('regency_kode'))->pluck('nama')->toArray()) : 'Tidak ada pilihan')
                                                ->label('Kabupaten/Kota'),
                    
                                            Forms\Components\Placeholder::make('districtPlaceHolder')
                                                ->content(fn(Get $get) => $get('district_kode') ? implode(', ', District::whereIn('kode', $get('district_kode'))->pluck('nama')->toArray()) : 'Tidak ada pilihan')
                                                ->label('Kecamatan'),
                    
                                            Forms\Components\Placeholder::make('villagePlaceHolder')
                                                ->content(fn(Get $get) => $get('village_kode') ? implode(', ', Village::whereIn('kode', $get('village_kode'))->pluck('nama')->toArray()) : 'Tidak ada pilihan')
                                                ->label('Kelurahan'),
                    
                                            Forms\Components\Placeholder::make('interestPlaceHolder')
                                                ->content(fn(Get $get) => $get('interest') ? implode(', ', $get('interest')) : 'Tidak ada pilihan')
                                                ->label('Interest'),
                                        ])
                                ])
                                ->collapsible(),
                    
                            Forms\Components\Section::make('Rincian Harga')
                                ->icon('heroicon-m-currency-dollar')
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\Placeholder::make('package_ratePlaceHolder')
                                                ->content(fn(Get $get) => $get('package_rate'))
                                                ->label('Paket'),
                                            Forms\Components\Placeholder::make('PricePlaceHolder')
                                                ->content(function (Get $get) {
                                                    $type = $get('type');
                                                    $price = \App\Models\PackageRate::where('type', $type)->value('price') ?? 0;
                                                    return 'Rp. ' . number_format($price, 0, ',', '.');
                                                })
                                                ->label('Harga Satuan'),
                    
                                            Forms\Components\Placeholder::make('totalPricePlaceHolder')
                                                ->content(function (Get $get) {
                                                    $type = $get('type');
                                                    $packageRate = $get('package_rate');
                                                    $price = \App\Models\PackageRate::where('type', $type)->value('price') ?? 0;
                                                    $total = $price * $packageRate;
                                                    return 'Rp. ' . number_format($total, 0, ',', '.');
                                                })
                                                ->label('Harga Total'),
                                            Forms\Components\Placeholder::make('finalPricePlaceHolder')
                                                ->content(function (Get $get) {
                                                    $price = \App\Models\PackageRate::where('type', $get('type'))->value('price') ?? 0;
                                                    $total = $price * $get('package_rate');
                                                    $additional = 0;
                            
                                                    if ($get('gender')) $additional += 10;
                                                    if ($get('generation')) $additional += 10;
                                                    if ($get('interest')) $additional += 10;
                            
                                                    $finalPrice = $total + ($total * $additional / 100);
                            
                                                    return 'Rp. ' . number_format($finalPrice, 0, ',', '.');
                                                })
                                                ->label('Total Harga Akhir'),
                                        ]),
                    
                                    Forms\Components\Placeholder::make('priceDetailsPlaceHolder')
                                        ->content(function (Get $get) {
                                            $details = [];
                    
                                            if ($get('gender')) $details[] = 'Gender +10%';
                                            if ($get('generation')) $details[] = 'Generasi +10%';
                                            $locations = ['province_kode', 'regency_kode', 'district_kode', 'village_kode'];
                                            foreach ($locations as $location) {
                                                if (!empty($get($location))) {
                                                    $details[] = 'Lokasi +10%';
                                                    break;
                                                }
                                            }
                                            if (!empty($get('interest'))) $details[] = 'Interest +10%';
                    
                                            return $details ? 'Keterangan Tambahan Harga: ' . implode(', ', $details) : 'Tidak ada tambahan harga.';
                                        })
                                        ->label('Detail Tambahan Harga')
                                        ->columnSpanFull(),
                                ])
                                ->collapsible(),
                        ])
                    
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('created_by', Auth::id());
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
