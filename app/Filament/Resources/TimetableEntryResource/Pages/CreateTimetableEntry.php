<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource;
use App\Models\SigEvent;
use App\Models\TimetableEntry;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Saade\FilamentFullCalendar\Actions\CreateAction;

class CreateTimetableEntry extends CreateRecord
{
    protected static string $resource = TimetableEntryResource::class;

    protected static ?string $cluster = SigPlanning::class;

    public function getHeading(): string
    {
        return __('Add Timetable Entry');
    }
    public function form(Form $form): Form {
        return $form->schema(self::getSchema());
    }

    public static function getCreateAction(CreateAction|\Filament\Tables\Actions\CreateAction $action): CreateAction|\Filament\Tables\Actions\CreateAction {
        return $action
            ->model(TimetableEntry::class)
//            ->authorize("create", TimetableEntry::class) // won't work (filament bug?)
            ->visible(Gate::check("create", TimetableEntry::class)) // real authorization is done in "mutateData()"
            ->fillForm(function(array $arguments, ?Model $record) {
                return [
                   'sig_event_id' => $record?->id ?? $arguments['sig_location_id'] ?? null,
                   'sig_location_id' => $arguments['resource']['id'] ?? null,
                   'entries' => [
                       [
                           'start' => $model?->start ?? $arguments['start'] ?? now()->addHour()->setMinute(0)->setSecond(0),
                           'end' => $model?->end ?? $arguments['end'] ?? now()->addHour()->setMinute(0)->setSecond(0)->addMinutes($record?->duration ?? 120),
                       ]
                   ],
                ];
            })
            ->modalFooterActionsAlignment(Alignment::End)
            ->createAnother(false)
            ->mutateFormDataUsing(fn($data) => self::mutateData($data))
            ->form(CreateTimetableEntry::getSchema())
            ->after(fn($livewire) => ($livewire->dispatch("refresh")))
            ->modelLabel(__("Timetable Entry"));

    }

    public static function mutateData(array $data): array {
        // read "entries" and remove them from $data array
        // entries => Filament Repeater for multiple event creation at once
        $entries = $data['entries'];
        unset($data['entries']);

        // take the last element for the regular model creation via filament
        $data['start'] = end($entries)['start'];
        $data['end'] = end($entries)['end'];

        // remove array
        array_pop($entries);

        Gate::authorize("create", TimetableEntry::class);

        // create entries from repeater manually
        foreach($entries AS $entry) {
            TimetableEntry::create(
                array_merge($data, [
                    'start' => $entry['start'],
                    'end' => $entry['end'],
                ])
            );
        }

        // client will be redirected to the last one
        return $data;
    }
    public function mutateFormDataBeforeCreate(array $data): array {
        return self::mutateData($data);
    }

    public static function getSchema(): array {
        return [
            TimetableEntryResource::getSigEventField(),
            TimetableEntryResource::getSigLocationField(),
            Repeater::make("entries")
                ->label("Entries")
                ->translateLabel()
                ->columns(2)
                ->reorderable(false)
                ->schema([
                    DateTimePicker::make('start')
                        ->label('Beginning')
                        ->translateLabel()
                        ->seconds(false)
                        ->columns(1)
                        ->live()
                        ->debounce()
                        ->afterStateUpdated(function(Set $set, $state, Get $get) {
                            $record = SigEvent::find($get('../../sig_event_id'));
                            if(filled($state)) {
                                $set('end', Carbon::parse($state)->addMinutes($record?->duration ?? 60)->toDateTimeLocalString());
                            }
                        })
                        ->required(),
                    DateTimePicker::make('end')
                        ->label('End')
                        ->translateLabel()
                        ->afterOrEqual('start')
                        ->seconds(false)
                        ->columns(1)
                        ->required(),
                ])
                ->addAction(function(Action $action) {
                    // duplicate last Repeater entry and add one day for bulk event creation
                    // TODO: das muss doch auch einfacher gehen, oder...?
                    $action->label("Add Entry")
                        ->translateLabel()
                        ->after(function(Set $set, Get $get, $state) {
                            array_pop($state);
                            $current = end($state);
                            $set('start', $current['start'] ?? now());
                            $set('end', $current['end'] ?? now());
                            $state[] = [
                                'start' => Carbon::parse($current['start'] ?? now())->addDay()->toDateTimeString('minute'),
                                'end' => Carbon::parse($current['end'] ?? now())->addDay()->toDateTimeString('minute'),
                            ];
                            $set('entries', $state);
                        });
                })
                ->columnSpan("full")
                ->defaultItems(1),
                Fieldset::make("Event Settings")
                    ->translateLabel()
                    ->schema([
                        TimetableEntryResource::getSigNewField(),
                        TimetableEntryResource::getSigHideField(),
                        TimetableEntryResource::getSigCancelledField(),
                    ])
                    ->columns(3),

                Fieldset::make("Communication Settings")
                    ->translateLabel()
                    ->schema([
                        TimetableEntryResource::getSendUpdateField(),
                    ])
                    ->hidden(fn(string $operation): bool => $operation == "create"),
        ];
}
}
