<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostChannelResource\Pages;
use App\Filament\Traits\HasActiveIcon;
use App\Models\Post\PostChannel;
use App\Services\PostChannels\PostChannelManager;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PostChannelResource extends Resource
{
    use HasActiveIcon;
    protected static ?string $model = PostChannel::class;
//    protected static ?string $cluster = Settings::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
//    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $navigationGroup = "Post";
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

    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Fieldset::make("Details")
                    ->translateLabel()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Select::make('implementation')
                            ->label("Implementation")
                            ->translateLabel()
                            ->options(PostChannelManager::options())
                            ->default(PostChannelManager::DEFAULT)
                            ->required()
                            ->dehydrateStateUsing(fn(?string $state) => PostChannelManager::normalize($state))
                            ->native(false),
                        Forms\Components\TextInput::make('channel_identifier')
                            ->label("Channel ID")
                            ->translateLabel()
                            ->required()
                            ->rules(fn(Get $get, ?Model $record) => [
                                Rule::unique('post_channels', 'channel_identifier')
                                    ->where('implementation', PostChannelManager::normalize($get('implementation')))
                                    ->ignore($record?->id),
                            ]),
                        Forms\Components\TextInput::make('test_channel_identifier')
                            ->label("Test Channel ID")
                            ->translateLabel()
                            ->dehydrateStateUsing(fn(?string $state) => blank($state) ? null : $state)
                            ->rules(fn(Get $get, ?Model $record) => filled($get('test_channel_identifier')) ? [
                                Rule::unique('post_channels', 'test_channel_identifier')
                                    ->where('implementation', PostChannelManager::normalize($get('implementation')))
                                    ->ignore($record?->id),
                            ] : []),
                        Forms\Components\TextInput::make('language')
                            ->label("Language")
                            ->translateLabel()
                            ->required()
                            ->maxLength(255)
                            ->default('de'),
                        Forms\Components\Toggle::make('default')
                            ->label("Default / Public")
                            ->translateLabel()
                            ->inline(false)
                            ->helperText("Selected by default when creating a new post? Every message in this channel will be publicly visible in the announcements tab."),
                        Forms\Components\RichEditor::make('info')
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
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->label("Language")
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => strtoupper($state)),
                Tables\Columns\TextColumn::make('implementation')
                    ->label("Implementation")
                    ->translateLabel()
                    ->badge()
                    ->formatStateUsing(fn($state) => PostChannelManager::options()[$state] ?? $state),
                Tables\Columns\TextColumn::make("channel_identifier")
                    ->label("Channel ID")
                    ->translateLabel(),
                Tables\Columns\TextColumn::make("test_channel_identifier")
                    ->label("Test Channel ID")
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->infolist(Pages\ViewPostChannel::getInfolistSchema()),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label(""),
            ])
            ->recordUrl(null)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ManagePostChannels::route('/'),
        ];
    }
}
