<?php

namespace App\Filament\Resources\SigHostResource\RelationManagers;

use App\Models\SigEvent;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class SigEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigEvents';

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('SIGs');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('timetable_entries_count')
                    ->label('In Schedule')
                    ->translateLabel()
                    ->counts('timetableEntries'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(route('filament.admin.resources.sig-events.create', [
                        'host_id' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn(SigEvent $entry) => route('filament.admin.resources.sig-events.edit', $entry)),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
