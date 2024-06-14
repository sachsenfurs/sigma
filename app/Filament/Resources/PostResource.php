<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TranslateAction;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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

    public static function canAccess(): bool {
        return auth()->user()->permissions()->contains("post");
    }

    public static function form(Form $form): Form {
        $channels = PostChannel::all();

        return $form
            ->schema([
                Forms\Components\MarkdownEditor::make('text')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\MarkdownEditor::make('text_en')
                    ->required()
                    ->hintAction(
                        TranslateAction::translateToSecondary("text", "text_en")
                    )
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->nullable(),
                Forms\Components\CheckboxList::make("channels")
                    ->options($channels->pluck("name", "id"))
                    ->default($channels->pluck("id")->toArray())
//                    ->dehydrated(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('text')
                    ->limit(50),
                Tables\Columns\TextColumn::make('text_en')
                    ->label("Text (English)")
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
