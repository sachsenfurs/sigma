<?php

namespace App\Filament\Resources\TimetableEntryResource\RelationManagers;

use App\Filament\Resources\SigEventResource\Pages\ViewSigEvent;
use App\Filament\Resources\TimetableEntryResource;
use App\Models\SigEvent;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class TimetableEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'timetableEntries';
    protected static ?string $icon = 'heroicon-o-rectangle-stack';

    public static function getPluralModelLabel(): ?string {
        return TimetableEntryResource::getPluralModelLabel();
    }

    public static function getModelLabel(): ?string {
        return TimetableEntryResource::getModelLabel();
    }
    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("In Schedule");
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string {
        return $ownerRecord->timetableEntries->count() ?: null;
    }

    public function table(Table $table): Table {
        $table = TimetableEntryResource::table($table)
            ->headerActions($this->getTableHeaderActions())
            ->recordUrl(fn($record) => Gate::allows("update", $record) ? TimetableEntryResource::getUrl("edit", ['record' => $record]) : null)
            ->actions($this->getTableEntryActions());

        $cols = $table->getColumns();
        if($this->ownerRecord instanceof SigEvent)
            unset($cols['sigEvent.name']);

        return $table->columns($cols);
    }

    protected function getTableColumns(): array {
        return TimetableEntryResource::getTableColumns();
    }

    protected function getTableHeaderActions(): array {
        return [
            TimetableEntryResource\Pages\CreateTimetableEntry::getCreateAction(
                Tables\Actions\CreateAction::make(),
                [
                    'sig_location_id' => ($this->ownerRecord instanceof SigLocation) ? $this->ownerRecord->id : null,
                    'sig_event_id' => ($this->ownerRecord instanceof  SigEvent) ? $this->ownerRecord->id : null,
                ]
            )
        ];
    }

    protected function getTableEntryActions(): array {
        return [
            Tables\Actions\ViewAction::make()
                ->infolist(fn($record) => (new Infolist())->schema(ViewSigEvent::getInfolistSchema())->record($record->sigEvent))
                ->hidden(fn(TimetableEntriesRelationManager $livewire) => $livewire->pageClass == ViewSigEvent::class),
            Tables\Actions\EditAction::make()
                ->form(TimetableEntryResource\Pages\EditTimetableEntry::getSchema())
                ->using(fn(Model $record, array $data) => TimetableEntryResource\Pages\EditTimetableEntry::handleUpdate($record, $data)),
        ];
    }
}
