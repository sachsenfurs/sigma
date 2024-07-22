<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostChannelResource\Pages;
use App\Filament\Resources\PostChannelResource\RelationManagers;
use App\Models\Post\PostChannel;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostChannelResource extends Resource
{
    protected static ?string $model = PostChannel::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = "Post";
    protected static ?int $navigationSort = 110;
    public static function getModelLabel(): string {
        return __("Channel");
    }

    public static function getPluralModelLabel(): string {
        return __("Channels");
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
                        Forms\Components\TextInput::make('channel_identifier')
                            ->label("Channel ID")
                            ->translateLabel()
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('test_channel_identifier')
                            ->label("Test Channel ID")
                            ->translateLabel()
                            ->numeric(),
                        Forms\Components\TextInput::make('language')
                            ->label("Language")
                            ->translateLabel()
                            ->required()
                            ->maxLength(255)
                            ->default('de'),
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
                    ->translateLabel()
                    ->formatStateUsing(fn($state) => strtoupper($state)),
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
