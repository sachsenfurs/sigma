<?php

namespace App\Filament\Resources\Ddas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Ddas\ArtshowArtistResource\Pages\ListArtshowArtists;
use App\Filament\Resources\Ddas\ArtshowArtistResource\Pages\CreateArtshowArtist;
use App\Filament\Resources\Ddas\ArtshowArtistResource\Pages\EditArtshowArtist;
use App\Filament\Helper\FormHelper;
use App\Filament\Resources\Ddas\ArtshowArtistResource\Pages;
use App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers\ArtshowItemRelationManager;
use App\Models\Ddas\ArtshowArtist;
use App\Settings\ArtShowSettings;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ArtshowArtistResource extends Resource
{
    protected static ?string $model = ArtshowArtist::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?int $navigationSort = 200;

    public static function getModelLabel(): string {
        return __("Artist");
    }
    public static function getPluralModelLabel(): string {
        return __("Artists");
    }

    public static function getLabel(): ?string {
        return __("Artist");
    }

    public static function getPluralLabel(): ?string {
        return __('Artists');
    }
    public static function getNavigationGroup(): ?string {
        return __("Art Show");
    }

    public static function canAccess(): bool {
        return parent::canAccess() AND app(ArtShowSettings::class)->enabled;
    }
    public static function form(Schema $schema): Schema {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(FormHelper::formatUserWithRegId()) // formatting when user already present
                    ->getSearchResultsUsing(FormHelper::searchUserByNameAndRegId())
                    ->searchDebounce(250),
                TextInput::make('name')
                    ->label('Artist Name')
                    ->translateLabel()
                    ->maxLength(255)
                    ->required(),
                TextInput::make('social')
                    ->label('Social Link')
                    ->translateLabel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->withCount("artshowItems"))
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Artist Name')
                    ->translateLabel()
                    ->searchable(),
                TextColumn::make('social')
                    ->label('Social Link')
                    ->translateLabel()
                    ->limit(40)
                    ->searchable(),
                TextColumn::make('artshow_items_count')
                    ->label("Item Count")
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array {
        return [
            ArtshowItemRelationManager::class
        ];
    }

    public static function getPages(): array {
        return [
            'index' => ListArtshowArtists::route('/'),
            'create' => CreateArtshowArtist::route('/create'),
            'edit' => EditArtshowArtist::route('/{record}/edit'),
        ];
    }
}
