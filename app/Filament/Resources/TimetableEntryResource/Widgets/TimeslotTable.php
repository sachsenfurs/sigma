<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Models\SigTimeslot;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class TimeslotTable extends BaseWidget
{
    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string|null|Htmlable
    {
        return __('Manage Time Slots');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SigTimeslot::query()
                    ->where('timetable_entry_id', $this->record->id)
            )
            ->columns($this->getTableColumns())
            ->headerActions($this->getTableHeaderActions())
            ->actions($this->getTableEntryActions())
            ->striped();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('slot_start')
                ->label('Slot Start')
                ->translateLabel()
                ->dateTime('H:i'),
            Tables\Columns\TextColumn::make('slot_end')
                ->label('Slot End')
                ->translateLabel()
                ->dateTime('H:i'),
            Tables\Columns\TextColumn::make('reg_start')
                ->label('Registration Start')
                ->translateLabel()
                ->dateTime('d.m.Y, H:i'),
            Tables\Columns\TextColumn::make('reg_end')
                ->label('Registration End')
                ->translateLabel()
                ->dateTime('d.m.Y, H:i'),
            Tables\Columns\TextColumn::make('max_users')
                ->label('Attendees')
                ->translateLabel()
                ->formatStateUsing(function (SigTimeslot $timeslot) {
                    return $timeslot->sigAttendees->count() . '/' . $timeslot->max_users;
                }),
        ];
    }

    protected function getTableEntryActions(): array
    {
        return [
            ViewAction::make('view')
                ->label('Attendees')
                ->translateLabel()
                ->icon('heroicon-s-users')
                ->modalWidth(MaxWidth::Medium)
                ->modalHeading(__('Attendee List'))
                ->infolist([
                    RepeatableEntry::make('sigAttendees')
                        ->label('')
                        ->schema([
                            TextEntry::make('user.name')
                                ->label('')
                                ->formatStateUsing(function ($record) {
                                    return $record->user->name . ' (' . __('Reg Number') . ': ' . ($record->user->reg_id ?? 'N/A') . ')';
                                }),
                        ])
                ]),
            EditAction::make('edit')
                ->label('Edit')
                ->translateLabel()
                ->modalWidth(MaxWidth::Medium)
                ->modalHeading(__('Edit Time Slot'))
                ->form($this->getTimeslotForm()),
            DeleteAction::make('delete')
                ->label('Delete')
                ->translateLabel()
                ->modalHeading(__('Delete Time Slot?'))
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make('addTimeslot')
                ->label('Add Time Slot')
                ->translateLabel()
                ->form($this->getTimeslotForm())
                ->modalWidth(MaxWidth::Medium)
                ->modalHeading(__('Create new time slot'))
                ->modalSubmitActionLabel(__('Create Time Slot'))
                ->action(function (array $data) {
                    SigTimeslot::create([
                        'timetable_entry_id' => $this->record->id,
                        'slot_start' => $data['slot_start'],
                        'slot_end' => $data['slot_end'],
                        'reg_start' => $data['reg_start'],
                        'reg_end' => $data['reg_end'],
                        'max_users' => $data['max_users'],
                    ]);
                })
        ];
    }

    public function getTimeslotForm(): array
    {
        return [
            DateTimePicker::make('slot_start')
                ->label('Slot Start')
                ->translateLabel()
                ->format('H:i')
                ->date(false)
                ->seconds(false)
                ->default($this->record->start->format('H:i'))
                ->required(),
            DateTimePicker::make('slot_end')
                ->label('Slot End')
                ->translateLabel()
                ->format('H:i')
                ->date(false)
                ->seconds(false)
                ->default($this->record->start->addMinutes(15)->format('H:i'))
                ->required(),
            DateTimePicker::make('reg_start')
                ->label('Registration Start')
                ->translateLabel()
                ->format('Y-m-d\TH:i')
                ->seconds(false)
                ->default($this->record->start->subHours(24)->format('Y-m-d\TH:i')),
            DateTimePicker::make('reg_end')
                ->label('Registration End')
                ->translateLabel()
                ->format('Y-m-d\TH:i')
                ->seconds(false)
                ->default($this->record->start->subMinutes(60)->format('Y-m-d\TH:i')),
            TextInput::make('max_users')
                ->label('Max. Attendees')
                ->translateLabel()
                ->type('number')
                ->minValue(1)
                ->default(1),
        ];
    }

}
