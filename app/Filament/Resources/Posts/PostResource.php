<?php

namespace App\Filament\Resources\Posts;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Posts\Pages\ManagePosts;
use App\Filament\Actions\TranslateAction;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use UnitEnum;

class PostResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = Post::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedChatBubbleBottomCenterText;

    protected static string | UnitEnum | null $navigationGroup = "Post";
    protected static ?int $navigationSort = 100;

    public static function getModelLabel(): string {
        return __("post");
    }
    public static function getPluralModelLabel(): string {
        return __("posts");
    }

    public static function form(Schema $schema): Schema {
        $channels = PostChannel::all();

        return $schema
            ->components([
                Textarea::make('text')
                    ->label("Text")
                    ->maxLength(fn(Get $get) => $get("image") ? 1023 : 4095)
                    ->hint(__("Telegram Markdown Syntax supported"))
                    ->helperText(__("Markdown Syntax: *bold*  _italic_  `monospace`"))
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),
                Textarea::make('text_en')
                    ->label("Text (English)")
                    ->maxLength(fn(Get $get) => $get("image") ? 1023 : 4095)
                    ->translateLabel()
                    ->rows(10)
                    ->hintAction(
                        fn($operation) => $operation != "view" ? TranslateAction::translateToSecondary("text", "text_en") : null
                    )
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label("Image")
                    ->translateLabel()
                    ->hidden(fn($operation, ?Model $record) => $operation == "edit" AND !$record?->image)
                    ->image()
                    ->preserveFilenames(false)
                    ->disk("public")
                    ->nullable(),
                CheckboxList::make("channels")
                    ->label("Channels")
                    ->translateLabel()
                    ->visibleOn('create')
                    ->options($channels->pluck("name", "id"))
                    ->formatStateUsing(fn(?Model $record) => $record?->channels?->pluck("id")?->toArray() ?? $channels->where("default")->pluck("id")->toArray())
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                IconColumn::make("deleted_at")
                    ->label("")
                    ->boolean()
                    ->visible(fn(Table $table) => $table->getFilters()['trashed']->getActiveCount())
                    ->getStateUsing(fn(Model $record) => $record->deleted_at == null),
                TextColumn::make('text')
                    ->label("Text")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))))
                    ->lineClamp(5)
                    ->limit(100),
                TextColumn::make('text_en')
                    ->label("Text (English)")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => new HtmlString(nl2br(htmlspecialchars($state))))
                    ->lineClamp(5)
                    ->limit(100),
                ImageColumn::make('image')
                    ->label("Image")
                    ->translateLabel(),
                TextColumn::make('created_at')
                    ->label("Created")
                    ->translateLabel()
                    ->dateTime()
                    ->formatStateUsing(fn(?Carbon $state) => $state?->diffForHumans())
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label("User")
                    ->translateLabel()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->defaultSort('created_at', 'DESC')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => ManagePosts::route('/'),
        ];
    }
}
