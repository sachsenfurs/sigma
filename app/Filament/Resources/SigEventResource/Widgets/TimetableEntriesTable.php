<?php

namespace App\Filament\Resources\SigEventResource\Widgets;

use App\Models\TimetableEntry;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class TimetableEntriesTable extends BaseWidget
{
    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string|null|Htmlable
    {
        return __('Schedule Entries');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TimetableEntry::query()
                    ->where('sig_event_id', $this->record->id)
            )
            ->columns($this->getTableColumns())
            ->headerActions($this->getTableHeaderActions())
            ->actions($this->getTableEntryActions())
            ->striped();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('start')
                ->label('Day')
                ->translateLabel()
                ->dateTime('H:i')
                ->formatStateUsing(function (TimetableEntry $entry) {
                    $timeStart = $entry->start->isoFormat('dddd, DD.MM.');
                    if ($entry->start->format("d.m.Y") != $entry->end->format("d.m.Y")) {
                        $timeStart .= ' - ' . $entry->end->format('d.m');
                    }
                    return $timeStart;
                }),
            Tables\Columns\TextColumn::make('timespan')
                ->label('Time span')
                ->translateLabel()
                ->getStateUsing(function (TimetableEntry $entry) {
                    return $entry->start->format('H:i') . ' - ' . $entry->end->format('H:i');
                }),
            Tables\Columns\TextColumn::make('sigLocation.name')
                ->label('Location')
                ->translateLabel(),
            Tables\Columns\TextColumn::make('timeslots_count')
                ->label('Time slots')
                ->translateLabel()
                ->getStateUsing(function (TimetableEntry $entry) {
                    return $entry->sigTimeslots->count();
                }),
        ];
    }

    protected function getTableEntryActions(): array
    {
        return [
            ViewAction::make('view')
                ->label('View')
                ->translateLabel()
                ->url(fn(TimetableEntry $entry) => route('filament.admin.sig-planning.resources.timetable-entries.edit', $entry)),
            EditAction::make('edit')
                ->label('Edit')
                ->translateLabel()
                ->modalWidth(MaxWidth::Medium)
                ->modalHeading(__('Edit Schedule Entry'))
                ->form($this->getTimetableEntryForm()),
            DeleteAction::make('delete')
                ->label('Delete')
                ->translateLabel()
                ->modalHeading(__('Delete Schedule Entry') . '?')
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make('addTimetableEntry')
                ->label('Create Schedule Entry')
                ->translateLabel()
                ->form($this->getTimetableEntryForm())
                ->modalWidth(MaxWidth::Medium)
                ->modalHeading(__('Create Schedule Entry'))
                ->modalSubmitActionLabel(__('Create Entry'))
                ->action(function (array $data) {
                    TimetableEntry::create([
                        'sig_event_id' => $this->record->id,
                        'sig_location_id' => $this->record->sig_location_id,
                        'start' => $data['start'],
                        'end' => $data['end'],
                    ]);
                })
        ];
    }

    public function getTimetableEntryForm(): array
    {
        return [
            DateTimePicker::make('start')
                ->label('Beginning')
                ->translateLabel()
                ->format('Y-m-d\TH:i')
                ->seconds(false)
                ->default(Carbon::now()->setMinutes(0)->format('Y-m-d\TH:i')),
            DateTimePicker::make('end')
                ->label('End')
                ->translateLabel()
                ->format('Y-m-d\TH:i')
                ->seconds(false)
                ->default(Carbon::now()->setMinutes(0)->addMinutes(60)->format('Y-m-d\TH:i')),
        ];
    }

}
