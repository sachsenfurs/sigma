<?php

namespace App\Filament\Resources\Ddas\ArtshowItemResource\RelationManagers;

use App\Filament\Resources\Ddas\ArtshowItemResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ArtshowItemRelationManager extends RelationManager
{
    protected static string $relationship = 'artshowItems';

    public function form(Form $form): Form {
        return ArtshowItemResource::form($form);
    }

    public function table(Table $table): Table {
        $table = ArtshowItemResource::table($table);
        $table->getColumn("artist.name")->hidden();

        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
