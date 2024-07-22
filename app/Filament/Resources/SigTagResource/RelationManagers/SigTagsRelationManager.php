<?php

namespace App\Filament\Resources\SigTagResource\RelationManagers;

use App\Models\SigEvent;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SigTagsRelationManager extends RelationManager
{
    protected static string $relationship = 'sigEvent';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        // Filament needs to know if the user can view the relation manager for the given record.
        return auth()->user()->can('manage_sigs');
    }

    protected function can(string $action, ?Model $record = null): bool
    {
        // Filament needs to know if the user can perform the given action on the relation manager.
        return auth()->user()->can('manage_sigs');
    }
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
            Tables\Actions\CreateAction::make()
                ->url(route('filament.admin.resources.sig-events.create', [
                    'tag_id' => $this->getOwnerRecord()->id,
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
