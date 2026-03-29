<?php

namespace App\Filament\Resources\PostChannels;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\ViewAction;
use App\Filament\Resources\PostChannels\Pages\ViewPostChannel;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\PostChannels\Pages\ManagePostChannels;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Post\PostChannel;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;
use UnitEnum;

class PostChannelResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = PostChannel::class;
//    protected static ?string $cluster = Settings::class;
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedMegaphone;
//    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static string | UnitEnum | null $navigationGroup = "Post";
    protected static ?int $navigationSort = 110;
    public static function getModelLabel(): string {
        return __("Channel");
    }

    public static function getPluralModelLabel(): string {
        return __("Channels");
    }

    public static function canAccess(): bool {
        return Gate::allows("viewAny", PostChannel::class);
    }

    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Fieldset::make("Details")
                    ->translateLabel()
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('channel_identifier')
                            ->label("Channel ID")
                            ->translateLabel()
                            ->required()
                            ->numeric(),
                        TextInput::make('test_channel_identifier')
                            ->label("Test Channel ID")
                            ->translateLabel()
                            ->numeric(),
                        TextInput::make('language')
                            ->label("Language")
                            ->translateLabel()
                            ->required()
                            ->maxLength(255)
                            ->default('de'),
                        Toggle::make('default')
                            ->label("Default / Public")
                            ->translateLabel()
                            ->inline(false)
                            ->helperText("Selected by default when creating a new post? Every message in this channel will be publicly visible in the announcements tab."),
                        RichEditor::make('info')
                            ->columnSpanFull()
                            ->label("Info (Internal)")
                            ->translateLabel()
                            ->maxLength(65535)
                    ]),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('language')
                    ->label("Language")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => strtoupper($state)),
                TextColumn::make("channel_identifier")
                    ->label("Channel ID")
                    ->translateLabel(),
                TextColumn::make("test_channel_identifier")
                    ->label("Test Channel ID")
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->schema(ViewPostChannel::getInfolistSchema()),
                EditAction::make(),
                DeleteAction::make()
                    ->label(""),
            ])
            ->recordUrl(null)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => ManagePostChannels::route('/'),
        ];
    }
}
