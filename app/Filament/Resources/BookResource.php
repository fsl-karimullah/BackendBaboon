<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('strings.publisher') => $record->publisher,
        ];
    }

    public static function getModelLabel(): string
    {
        return __('strings.book');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    FileUpload::make('thumbnail')
                        ->image()
                        ->imagePreviewHeight(400)
                        ->maxSize(1048)
                        ->columnSpan([
                            'md' => 2
                        ])
                        ->label(__('strings.cover_picture'))
                        ->directory('bookThumbnails')
                        ->required(),
                    TextInput::make('isbn')
                        ->label('ISBN')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->length(13)
                        ->suffixAction(
                            fn ($state, Closure $set) => Action::make('search-action')
                                ->icon('heroicon-o-search')
                                ->action(function () use ($state, $set) {
                                    if (blank($state)) {
                                        Filament::notify('danger', __('strings.please_enter_isbn_number'));

                                        return;
                                    }

                                    try {
                                        $bookData = Http::baseUrl('https://www.googleapis.com/books/v1/')
                                            ->get('volumes', ['q' => "isbn:$state"])
                                            ->throw()
                                            ->json('items');
                                        $book = $bookData[0]['volumeInfo'] ?? null;
                                    } catch (RequestException $e) {
                                        Filament::notify('danger', __('strings.failed_finding_book_data'));

                                        return;
                                    }

                                    if ($book) {
                                        try {
                                            $set('title', $book['title']);
                                            $set('publisher', $book['publisher']);
                                            $set('authors', $book['authors']);
                                            $set('description', $book['description']);
                                            $set('page_count', $book['pageCount']);
                                            $set('published_date', $book['publishedDate']);
                                        } catch (\Throwable $th) {
                                        }
                                    } else {
                                        Filament::notify('danger', __('strings.failed_finding_book_data'));
                                    }
                                })
                        ),
                    TextInput::make('title')
                        ->required()
                        ->maxLength(100)
                        ->label(__('strings.title')),
                    TextInput::make('publisher')
                        ->required()
                        ->label(__('strings.publisher')),
                    TagsInput::make('authors')
                        ->label(__('strings.author'))
                        ->placeholder(null)
                        ->required(),
                    TextInput::make('page_count')
                        ->required()
                        ->label(__('strings.page_count')),
                    DatePicker::make('published_date')
                        ->required()
                        ->maxDate(now())
                        ->label(__('strings.date_published')),
                    TextInput::make('price')
                        ->numeric()
                        ->mask(fn (TextInput\Mask $mask) => $mask->money('Rp', '.', 0))
                        ->label(__('strings.price'))
                        ->required(),
                    Select::make('category_id')
                        ->searchable()
                        ->preload()
                        ->relationship('category', 'name')
                        ->label(__('strings.category'))
                        ->required(),
                    Textarea::make('description')
                        ->label(__('strings.description'))
                        ->columnSpan([
                            'md' => 2
                        ])
                        ->required(),
                ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->label(__('strings.title'))
                    ->limit(),
                TextColumn::make('publisher')
                    ->sortable()
                    ->searchable()
                    ->label(__('strings.publisher')),
                TagsColumn::make('authors')
                    ->label(__('strings.author')),
                TextColumn::make('price')
                    ->sortable()
                    ->money('idr', true)
                    ->searchable()
                    ->label(__('strings.price')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->latest();
    }
}
