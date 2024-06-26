<?php

namespace App\Filament\Resources\SigHostResource\RelationManagers;

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
            Tables\Columns\TextColumn::make('sigHost.name')
                ->label('Host')
                ->translateLabel()
                ->searchable()
                ->formatStateUsing(function (Model $record) {
                    $regNr = $record->sigHost->reg_id ? ' (' . __('Reg Number') . ': ' . $record->sigHost->reg_id . ')' : '';
                    return $record->sigHost->name . $regNr;
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
            Tables\Actions\CreateAction::make()
                ->url(route('filament.admin.resources.sig-events.create', [
                    'host_id' => $this->getOwnerRecord()->id,
                ])),
        ];
    }

    protected function getTableEntryActions(): array
    {
        return [
            Tables\Actions\EditAction::make()
                ->url(fn(SigEvent $entry) => route('filament.admin.resources.sig-events.edit', $entry)),
        ];
    }
}
