<?php

namespace App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use App\Filament\Resources\Ddas\ArtshowItemResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ArtshowItemRelationManager extends RelationManager
{
    protected static string $relationship = 'artshowItems';

    public function form(Schema $schema): Schema {
        return ArtshowItemResource::form($schema);
    }

    protected function getTableHeading(): string|Htmlable|null {
        return __("Art Show Items");
    }

    public function table(Table $table): Table {
        $table = ArtshowItemResource::table($table);
        $table->getColumn("artist.name")->hidden();

        return $table
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
