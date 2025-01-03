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
use Carbon\Carbon;
use Filament\Forms\Components\Grid;

class JobResource extends Resource
{
    protected static ?string $model = JobCampaign::class;

    protected static ?string $navigationGroup = 'Manage Campaign';
    protected static ?string $navigationLabel = 'Job Campaign';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $pluralModelLabel = 'Campaign Job ';
    protected static ?string $pluralLabel = 'Campaign Job';
    protected static ?string $modelLabel = 'Campaign Job';
    protected static ?string $label = 'Campaign Job';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([

                    Wizard\Step::make('Job Campaign')
                        ->schema([
                            Forms\Components\Select::make('type')
                                ->options([
                                    JobType::KOMENTAR->value => 'Komentar',
                                    JobType::VIEW->value => 'View',
                                    JobType::POSTING->value => 'Posting',
                                    JobType::SHARE_RETWEET->value => 'Share retweet',
                                    JobType::RATING_REVIEW->value => 'Rating & Review',
                                    JobType::DOWNLOAD_RATING_REVIEW->value => 'Download, Rating, Review',
                                    JobType::LIKE_POLLING_VOTE->value => 'Like Polling Vote',
                                    JobType::SURVEI->value => 'Survei',
                                    JobType::SUBSCRIBE_FOLLOW->value => 'Subscribe Follow',
                                    JobType::FOLLOW_MARKETPLACE->value => 'Follow Marketplace',

                                ])
                                ->searchable()
                                ->required(),

                            Forms\Components\TextInput::make('platform')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('quota')
                                ->required()
                                ->numeric()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('reward')
                                ->required()
                                ->numeric(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'publish' => 'Publish',
                                    'draft' => 'Draft',
                                ])
                                ->required(),
                            Grid::make(2)
                                ->schema([
                                    Forms\Components\DatePicker::make('start_date')
                                        ->live()
                                        ->reactive()
                                        ->required(),
                                    Forms\Components\DatePicker::make('end_date')
                                        ->live()
                                        ->reactive()
                                        ->required()
                                        ->rules(['after_or_equal:start_date']),
                                ]),
                            Forms\Components\Toggle::make('is_multiple')
                                ->required(),
                        ]),
                    Wizard\Step::make('Job Detail')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                            Forms\Components\FileUpload::make('jobDetail.image')
                                ->required()
                                ->image()
                                ->disk('public')
                                ->imageEditor()
                                ->imageCropAspectRatio('16:9')
                                ->imageResizeMode('cover')
                                ->imageResizeTargetWidth('1024')
                                ->imageResizeTargetHeight('576'),
                            Forms\Components\TextInput::make('jobDetail.description')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('instructions')
                            ->toolbarButtons([
                                'bulletList',
                                'orderedList',
                                'redo',
                                'undo',
                            ])
                                ->required(),
                            Forms\Components\TextInput::make('jobDetail.url_link')
                                ->required()
                                ->url()
                                ->maxLength(255),
                        ]),
                ])->columnSpanFull()
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="md"
                        >
                            Submit
                        </x-filament::button>
                    BLADE)))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('platform'),
                Tables\Columns\TextColumn::make('quota'),
                Tables\Columns\TextColumn::make('reward'),
                Tables\Columns\TextColumn::make('status')->badge()->icon(fn ($state) => match ($state) {
                    'publish' => 'heroicon-o-check-circle',
                    'draft' => 'heroicon-o-exclamation-circle',
                })
                ->color(fn ($state) => match ($state) {
                    'publish' => 'success',
                    'draft' => 'warning',
                }),
                Tables\Columns\TextColumn::make('start_date')->date()->toggleable(),
                Tables\Columns\TextColumn::make('end_date') ->date()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),

            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
