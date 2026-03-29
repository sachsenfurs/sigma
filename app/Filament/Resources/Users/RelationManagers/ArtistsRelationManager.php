<?php

namespace App\Filament\Resources\Users\RelationManagers;

use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\CreateAction;
use App\Filament\Resources\Ddas\ArtshowArtists\ArtshowArtistResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtistsRelationManager extends RelationManager
{
    protected static string $relationship = 'artists';
    protected static string | BackedEnum | null $icon = 'heroicon-o-paint-brush';

    public static function getModelLabel(): ?string {
        return __("Artist");
    }

    public static function getPluralModelLabel(): ?string {
        return __("Artists");
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("Artists");
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->artists()->count();
    }

    public function form(Schema $schema): Schema {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        $table = ArtshowArtistResource::table($table);
        $table->getColumn("user.name")->hidden();
        return $table
            ->recordUrl(fn(Model $record) => ArtshowArtistResource::getUrl("edit", ["record" => $record]))
            ->recordActions([])
            ->headerActions([
                CreateAction::make()
                    ->schema(fn($form) => ArtshowArtistResource::form($form))
                    ->fillForm([
                        'user' => $this->ownerRecord->id,
                    ]),
            ]);
    }
}
