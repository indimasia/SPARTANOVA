<?php

namespace App\Filament\Resources;

use App\Models\Article;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\ArticlesResource\Pages\EditArticle;
use App\Filament\Resources\ArticlesResource\Pages\ListArticle;
use App\Filament\Resources\ArticlesResource\Pages\CreateArticle;



class ArticleResource extends Resource {
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Artikel Edukasi';
    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('status')
                    ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Published At'),
                    FileUpload::make('image')
    ->image()
    ->disk('r2')
    ->directory('admin/article')
    ->visibility('public'),
                RichEditor::make('content')
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                ImageColumn::make('image')
                ->default('https://placehold.co/400x400?text=Tidak+Ada+Gambar')
                ->size(50)
                ->getStateUsing(fn ($record) => $record->image 
                    ? URL::route('storage.fetch', ['filename' => $record->image]) 
                    : null),

                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('published_at')->dateTime(),
                TextColumn::make('created_at')->since(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => ListArticle::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
