<?php

namespace App\Filament\Resources\Ddas;

use App\Filament\Resources\Ddas\ArtshowArtistResource\Pages;
use App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers\ArtshowItemRelationManager;
use App\Models\Ddas\ArtshowArtist;
use App\Models\User;
use App\Settings\ArtShowSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ArtshowArtistResource extends Resource
{
    protected static ?string $model = ArtshowArtist::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

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
    public static function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->translateLabel()
                    ->searchable()
                    ->formatStateUsing(fn(?Model $record) => $record != null ? $record->user_id . " - " . $record->user->name : "") // formatting when user already present
                    ->getSearchResultsUsing(function (string $search) {
                        return User::where('name', 'like', "%{$search}%")
                            ->orWhere('id', $search)
                            ->limit(10)
                            ->get()
                            ->map(fn($u) => [$u->id => $u->id . " - " . $u->name]) // formatting when searching for new user
                            ->toArray();
                    })
                    ->searchDebounce(250),
                Forms\Components\TextInput::make('name')
                    ->label('Artist Name')
                    ->translateLabel()
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('social')
                    ->label('Social Link')
                    ->translateLabel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->withCount("artshowItems"))
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Artist Name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('social')
                    ->label('Social Link')
                    ->translateLabel()
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('artshow_items_count')
                    ->label("Item Count")
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListArtshowArtists::route('/'),
            'create' => Pages\CreateArtshowArtist::route('/create'),
            'edit' => Pages\EditArtshowArtist::route('/{record}/edit'),
        ];
    }
}
