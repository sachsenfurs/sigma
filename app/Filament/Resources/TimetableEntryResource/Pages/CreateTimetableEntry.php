<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource;
use App\Models\TimetableEntry;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

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
                ->columns(2)
                ->schema([
                    DateTimePicker::make('start')
                        ->label('Beginning')
                        ->translateLabel()
                        ->format('Y-m-d\TH:i')
                        ->default(now()->addHour()->setMinutes(0)->setSeconds(0))
                        ->seconds(false)
                        ->columns(1)
                        ->required(),
                    DateTimePicker::make('end')
                        ->label('End')
                        ->translateLabel()
                        ->format('Y-m-d\TH:i')
                        ->default(now()->addHours(3)->setMinutes(0)->setSeconds(0))
                        ->seconds(false)
                        ->columns(1)
                        ->required(),
                ])
                ->addAction(function(Action $action) {
                    // TODO: das muss doch auch einfacher gehen, oder...?
                    $action->after(function(Set $set, Get $get, $state) {
                        array_pop($state);
                        $current = end($state);
                        $set('start', $current['start']);
                        $set('end', $current['end']);
                        $state[] = [
                            'start' => Carbon::parse($current['start'])->addDay()->toDateTimeString('minute'),
                            'end' => Carbon::parse($current['end'])->addDay()->toDateTimeString('minute'),
                        ];
                        $set('entries', $state);
                    });
                })
//                ->dehydrated(false)
//                ->dehydrateStateUsing(function($state) {
//                    return end($state);
//                })
                ->columnSpan("full")
                ->defaultItems(1),
                Fieldset::make("Event Settings")
                    ->schema([
                        TimetableEntryResource::getSigNewField(),
                        TimetableEntryResource::getSigHideField(),
                        TimetableEntryResource::getSigCancelledField(),
                    ])
                    ->columns(3),

                Fieldset::make("Communication Settings")
                    ->schema([
                        TimetableEntryResource::getSendUpdateField(),
                    ])
                    ->hidden(fn(string $operation): bool => $operation == "create")
            ,

        ];
}
}
