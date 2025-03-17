<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\Ddas\ArtshowArtistResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArtistsRelationManager extends RelationManager
{
    protected static string $relationship = 'artists';
    protected static ?string $icon = 'heroicon-o-paint-brush';

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

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table {
        $table = ArtshowArtistResource::table($table);
        $table->getColumn("user.name")->hidden();
        return $table
            ->recordUrl(fn(Model $record) => ArtshowArtistResource::getUrl("edit", ["record" => $record]))
            ->actions([])
            ->headerActions([
                CreateAction::make()
                    ->form(fn($form) => ArtshowArtistResource::form($form))
                    ->fillForm([
                        'user' => $this->ownerRecord->id,
                    ]),
            ]);
    }
}
