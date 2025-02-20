<?php

namespace App\Filament\Pengiklan\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Livewire\Topup;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use App\Models\ConversionRate;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Pengiklan\Resources\TopupResource\Pages;
use App\Filament\Pengiklan\Resources\TopupResource\RelationManagers;

class TopupResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Wizard::make([
                Wizard\Step::make('Pilih Nominal')
                ->schema([
                    Radio::make('amount')
                    ->label('Pilih Nominal Top Up')
                    ->options(function () {
                        $conversionRate = ConversionRate::first(); // Ambil data pertama
                        if (!$conversionRate) {
                            return []; // Jika tidak ada data, kosongkan opsi
                        }
    
                        $conversionRateValue = $conversionRate->conversion_rate;
    
                        return [
                            50000 => 'Rp 50,000' . ' (' . round(50000 / $conversionRateValue) . ' Poin)',
                            100000 => 'Rp 100,000' . ' (' . round(100000 / $conversionRateValue) . ' Poin)',
                            250000 => 'Rp 250,000' . ' (' . round(250000 / $conversionRateValue) . ' Poin)',
                            500000 => 'Rp 500,000' . ' (' . round(500000 / $conversionRateValue) . ' Poin)',
                            1000000 => 'Rp 1,000,000' . ' (' . round(1000000 / $conversionRateValue) . ' Poin)',
                        ];
                    })
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function ($state, $set) {
                        // Simpan nilai amount ke session setelah state berubah
                        session(['amount' => $state]);
                    }),

                    ]),

                Wizard\Step::make('Instruksi Pembayaran')
                    ->schema([
                        Section::make('Detail Pembayaran')
                            ->schema([
                                Radio::make('bank_account')
                                    ->label('Pilih Rekening Tujuan')
                                    ->options([
                                        'BCA' => 'BCA - 1234567890 (PT Example Company)',
                                        'Mandiri' => 'Mandiri - 0987654321 (PT Example Company)',
                                    ])
                                    ->reactive() // Agar bisa digunakan di step tinjauan
                                    ->required()
                                    ->afterStateUpdated(function ($state, $set) {
                                        // Simpan nilai bank_account ke session setelah state berubah
                                        session(['bank_account' => $state]);
                                    }),
                            ])
                    ]),

                    Wizard\Step::make('Unggah Bukti')
                    ->schema([
                        Section::make('Ringkasan Pembayaran')
                            ->schema([
                                Forms\Components\ViewField::make('amount_view')
                                    ->label('Nominal Top Up')
                                    ->view('filament.forms.components.copyable-placeholder', [
                                        'amount' => 'Rp ' . number_format((int) (session('amount') ?? 0), 0, ',', '.')
                                    ]),



                
                                Forms\Components\ViewField::make('bank_view')
                                    ->label('Transfer ke')
                                    ->view('filament.forms.components.copy-field', [
                                        'bank_account' => match (session('bank_account')) {
                                            'BCA' => '1234567890',
                                            'Mandiri' => '0987654321',
                                            default => 'Pilih rekening terlebih dahulu',
                                        }
                                    ]),

                                    Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('jenis_bank_view')
                                            ->label('Jenis Bank')
                                            ->content(fn (): string => match (session('bank_account')) {
                                                'BCA' => 'BCA',
                                                'Mandiri' => 'Mandiri',
                                                default => 'Pilih rekening terlebih dahulu',
                                            }),
                                
                                        Forms\Components\Placeholder::make('atas_nama_view')
                                            ->label('Atas Nama')
                                            ->content(fn (): string => match (session('bank_account')) {
                                                'BCA' => 'PT Example Company',
                                                'Mandiri' => 'PT Example Company',
                                                default => 'Pilih rekening terlebih dahulu',
                                            }),
                                    ])
                                    ->columns(2) // Membuat grid dengan 2 kolom
                                    ->columnSpan('full'),
                
                                    Forms\Components\ViewField::make('instructions_placeholder')
                                    ->label('Instruksi Pembayaran')
                                    ->view('filament.forms.components.instructions', [
                                        'instructions' => '• Pastikan nominal yang ditransfer sesuai dengan yang dipilih.<br>' .
                                                           '• Gunakan metode transfer sesuai pilihan rekening.<br>' .
                                                           '• Setelah transfer, unggah bukti pada langkah berikutnya.'
                                    ])
                                    ->extraAttributes(['class' => 'instruction-text']),
                                
                                
                
                                FileUpload::make('transfer_proof')
                                    ->label('Unggah Bukti Transfer')
                                    ->image()
                                    ->disk('r2')
                                    ->directory('pengiklan/topup/'.auth()->user()->id)
                                    ->visibility('public')
                                    ->maxSize(1024)
                                    ->required(),
                            ])
                    ]),
                
            ])
            ->columnSpanFull()
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
                TextColumn::make('user.name')->label('User'),
                TextColumn::make('amount')->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))->sortable(),
                TextColumn::make('bank_account')->label('Rekening Tujuan'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                ImageColumn::make('transfer_proof')->label('Bukti Transfer')->disk('r2')->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')->size(50)->getStateUsing(fn ($record) => $record->transfer_proof 
                    ? URL::route('storage.fetch', ['filename' => $record->transfer_proof]) 
                    : null),
                TextColumn::make('created_at')->label('Tanggal')->dateTime(),
            ])
            ->filters([
                // Bisa ditambahkan filter status jika diperlukan
            ]);
    }

    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopups::route('/'),
            'create' => Pages\CreateTopup::route('/create'),
            'edit' => Pages\EditTopup::route('/{record}/edit'),
        ];
    }
}