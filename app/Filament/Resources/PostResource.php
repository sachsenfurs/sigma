<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = "Post";
    protected static ?int $navigationSort = 100;

    public static function getModelLabel(): string {
        return __("post");
    }
    public static function getPluralModelLabel(): string {
        return __("posts");
    }

    public static function form(Form $form): Form {
        $channels = PostChannel::all();

        return $form
            ->schema([
                Forms\Components\Textarea::make('text')
                    ->label("Text")
                    ->hint(__("Telegram Markdown Syntax supported"))
                    ->helperText(__("Markdown Syntax: *bold*  _italic_  `monospace`"))
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('text_en')
                    ->label("Text (English)")
                    ->translateLabel()
                    ->rows(10)
                    ->hintAction(
                        fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary("text", "text_en") : null
                    )
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->label("Image")
                    ->translateLabel()
                    ->hidden(fn($operation, ?Model $record) => $operation == "edit" AND !$record?->image)
                    ->image()
                    ->preserveFilenames(false)
                    ->disk("public")
                    ->nullable(),
                Forms\Components\CheckboxList::make("channels")
                    ->label("Channels")
                    ->translateLabel()
                    ->visibleOn('create')
                    ->options($channels->pluck("name", "id"))
                    ->formatStateUsing(fn(?Model $record) => $record?->channels?->pluck("id")?->toArray() ?? $channels->pluck("id")->toArray())
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make("deleted_at")
                    ->label("")
                    ->boolean()
                    ->visible(fn(Table $table) => $table->getFilters()['trashed']->getActiveCount())
                    ->getStateUsing(fn(Model $record) => $record->deleted_at == null),
                Tables\Columns\TextColumn::make('text')
                    ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))))
                    ->lineClamp(5)
                    ->limit(100),
                Tables\Columns\TextColumn::make('text_en')
                    ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))))
                    ->lineClamp(5)
                    ->limit(100),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->formatStateUsing(fn(?Carbon $state) => $state?->diffForHumans())
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->defaultSort('created_at', 'DESC')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ManagePosts::route('/'),
        ];
    }
}
