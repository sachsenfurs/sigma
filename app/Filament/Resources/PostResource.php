<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Nette\Utils\Html;

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
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('text_en')
                    ->rows(10)
                    ->hintAction(
                        TranslateAction::translateToSecondary("text", "text_en")
                    )
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->hidden(fn($operation, ?Model $record) => $operation == "edit" AND !$record?->image)
                    ->image()
                    ->preserveFilenames(false)
                    ->disk("public")
                    ->nullable(),
                Forms\Components\CheckboxList::make("channels")
                    ->visibleOn('create')
                    ->options($channels->pluck("name", "id"))
                    ->formatStateUsing(fn(?Model $record) => $record?->channels?->pluck("id")?->toArray() ?? $channels->pluck("id")->toArray())
                    ->dehydrated(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('text')
                    ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))))
                    ->lineClamp(5)
                    ->limit(100)
                ,
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
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePosts::route('/'),
        ];
    }
}
