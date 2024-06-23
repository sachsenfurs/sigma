<?php

namespace App\Filament\Resources\TimetableEntryResource\RelationManagers;

use App\Filament\Resources\SigEventResource;
use App\Filament\Resources\TimetableEntryResource;
use App\Models\SigEvent;
use App\Models\SigLocation;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use function Filament\Support\format_money;

class TimetableEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'timetableEntries';

    public static function getPluralModelLabel(): ?string {
        return TimetableEntryResource::getPluralModelLabel();
    }

    public static function getModelLabel(): ?string {
        return TimetableEntryResource::getModelLabel();
    }
    public static function getTitle(Model $ownerRecord, string $pageClass): string {
        return __("In Schedule");
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool {
        // Filament needs to know if the user can view the relation manager for the given record.
        return auth()->user()->can("view", $ownerRecord);
    }

    protected function can(string $action, ?Model $record = null): bool {
        // Filament needs to know if the user can perform the given action on the relation manager.
        return auth()->user()->can("viewAny", $record);
    }

    public function table(Table $table): Table {
        $table = TimetableEntryResource::table($table)
            ->headerActions($this->getTableHeaderActions())
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
                Tables\Actions\CreateAction::make()
            )
            ->fillForm(fn() => [
                'sig_location_id' => ($this->ownerRecord instanceof SigLocation) ? $this->ownerRecord->id : null,
                'sig_event_id' => ($this->ownerRecord instanceof  SigEvent) ? $this->ownerRecord->id : null,
                'entries' => [[
                    'start' => now()->addHour()->setMinutes(0),
                    'end' => now()->addHours(2)->setMinutes(0),
                ]]
            ])
        ];
    }

    protected function getTableEntryActions(): array {
        return [
            Tables\Actions\EditAction::make()
                ->form(TimetableEntryResource\Pages\EditTimetableEntry::getSchema())
                ->using(fn(Model $record, array $data) => TimetableEntryResource\Pages\EditTimetableEntry::handleUpdate($record, $data)),
        ];
    }
}
