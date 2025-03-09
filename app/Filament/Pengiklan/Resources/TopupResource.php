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
use App\Models\TopUpTransaction;
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
                        $conversionRate = ConversionRate::first();
                        if (!$conversionRate) {
                            return [];
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
                        session(['amount' => $state]);
                    }),

                    ]),

                Wizard\Step::make('Instruksi Pembayaran')
                    ->schema([
                        Section::make('Detail Pembayaran')
                            ->schema([
                                Radio::make('top_up_transactions_id')
                                    ->label('Pilih Rekening Tujuan')
                                    ->options(function(Get $get) {
                                        return TopUpTransaction::all()
                                            ->mapWithKeys(function ($item) {
                                                return [$item->id => "{$item->nama_bank} - {$item->no_rekening} ({$item->nama_pemilik})"];
                                            });
                                    })
                                    
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $rekening = TopUpTransaction::find($state);
                                    
                                        if ($rekening) {
                                            session([
                                                'top_up_transactions_id' => $rekening->nama_bank,
                                                'bank_account_id' => $rekening->id,
                                                'bank_account_no_rekening' => $rekening->no_rekening,
                                                'bank_account_nama_pemilik' => $rekening->nama_pemilik,
                                            ]);
                                        }
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
                                        'bank_account' => session('top_up_transactions_id') 
                                            ? session('top_up_transactions_id') . ' - ' . session('bank_account_no_rekening') . ' (' . session('bank_account_nama_pemilik') . ')'
                                            : 'Pilih rekening terlebih dahulu',
                                    ]),

                                    Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('jenis_bank_view')
                                            ->label('Jenis Bank')
                                            ->content(fn (): string => session('top_up_transactions_id') ?? 'Pilih rekening terlebih dahulu'),
                                
                                        Forms\Components\Placeholder::make('atas_nama_view')
                                            ->label('Atas Nama')
                                            ->content(fn (): string => session('bank_account_nama_pemilik') ?? 'Pilih rekening terlebih dahulu'),
                                    ])
                                    ->columns(2)
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
                TextColumn::make('topUpTransaction.nama_bank')->label('Rekening Tujuan'),
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
                //
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