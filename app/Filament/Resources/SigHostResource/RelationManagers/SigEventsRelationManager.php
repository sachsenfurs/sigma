<?php

namespace App\Filament\Resources\SigHostResource\RelationManagers;

use App\Filament\Resources\SigEventResource;
use App\Models\SigEvent;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigEventsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigEvents';

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Assigned SIGs'))
            ->recordTitleAttribute('name')
            ->columns(self::getTableColumns())
            ->headerActions(self::getTableHeaderActions())
            ->actions(self::getTableEntryActions());
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->translateLabel()
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('sigHosts.name')
                ->label('Host')
                ->translateLabel()
                ->searchable()
                ->formatStateUsing(function (Model $record) {
                    return $record->sigHosts->map(fn($host) => $host->name . ($host->reg_id ? " (# ".$host->reg_id . ")" : ""))->join(", ");
                })
                ->sortable(),
            Tables\Columns\ImageColumn::make('languages')
                ->label('Languages')
                ->translateLabel()
                ->view('filament.tables.columns.sig-event.flag-icon'),
            Tables\Columns\TextColumn::make('timetable_entries_count')
                ->label('In Schedule')
                ->translateLabel()
                ->counts('timetableEntries'),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getTableEntryActions(): array
    {
        return [
            Tables\Actions\EditAction::make()
                 ->url(fn(SigEvent $entry) => SigEventResource::getUrl("edit", ['record' => $entry])),
            Tables\Actions\ViewAction::make()
                ->url(fn(SigEvent $entry) => SigEventResource::getUrl('view', ['record' => $entry]))
                ->hidden(fn(Model $record) => auth()->user()->can("update", $record)),
        ];
    }
}
