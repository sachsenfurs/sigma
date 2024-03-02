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
            ->columns(self::getTableColumns())
            ->headerActions(self::getTableHeaderActions())
            ->actions(self::getTableEntryActions());
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name'),
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
