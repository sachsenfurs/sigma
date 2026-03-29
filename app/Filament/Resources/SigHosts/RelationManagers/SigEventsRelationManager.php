<?php

namespace App\Filament\Resources\SigHosts\RelationManagers;

use Filament\Actions\EditAction;
use App\Filament\Resources\SigEvents\SigEventResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class SigEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigEvents';

    public function table(Table $table): Table {
        return SigEventResource::table($table)
            ->heading(__('Assigned SIGs'))
            ->recordActions([
                EditAction::make()
                    ->url(fn($record) => SigEventResource::getUrl("edit", ['record' => $record])),
            ])
            ->recordAction("view")
            ->recordUrl(null);
    }

}
