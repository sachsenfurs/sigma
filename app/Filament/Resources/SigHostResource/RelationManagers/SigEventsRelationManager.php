<?php

namespace App\Filament\Resources\SigHostResource\RelationManagers;

use App\Filament\Resources\SigEventResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SigEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigEvents';

    public function table(Table $table): Table {
        return SigEventResource::table($table)
            ->heading(__('Assigned SIGs'))
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => SigEventResource::getUrl("edit", ['record' => $record])),
            ])
            ->recordAction("view")
            ->recordUrl(null);
    }

}
