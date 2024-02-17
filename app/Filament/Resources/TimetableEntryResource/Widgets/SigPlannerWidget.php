<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Models\TimetableEntry;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class SigPlannerWidget extends FullCalendarWidget
{
    public Model | string | null $model = TimetableEntry::class;


    public function fetchEvents(array $info): array {
        return TimetableEntry::query()
             ->where(function($query) use ($info) {
                 $query->where('start', '>=', $info['start'])
                       ->where('start', '<=', $info['end']);
             })
             ->orWhere('end', '>=', $info['start'])
             ->get()
             ->map(
                fn (TimetableEntry $entry) => EventData::make()
                  ->id($entry->id)
                  ->title($entry->sigEvent->name)
                  ->start($entry->start->toDateTimeLocalString())
                  ->end($entry->end->toDateTimeLocalString())
//                  ->allDay($entry->duration >= 1339)
//                  ->url(
//                      url: "/",
//                      shouldOpenUrlInNewTab: true
//                  )
                  ->extraProperties([
                      'resourceId' => $entry->sigLocation->id,
                      'backgroundColor' => $entry->hide ? "rgb(113, 113, 113)" : "",
                  ])
             )
                             ->toArray();
    }

    protected function headerActions(): array {
        return [
            CreateAction::make()
                ->mountUsing(function(Form $form, array $arguments) {
                    $form->fill([
                        'start' => $arguments['start'] ?? null,
                        'end' => $arguments['end'] ?? null,
                        'sig_location_id' => $arguments['resource']['id'] ?? null,
                    ]);
                }),
        ];
    }

    protected function modalActions(): array {
        return [
            EditAction::make()
                  ->mountUsing(function(TimetableEntry $entry, Form $form, array $arguments) {
                      $start    = $arguments['event']['start'] ?? $entry->start;
                      $end      = $arguments['event']['end'] ?? $entry->end;

//                      if($arguments['event']['allDay'] ?? false) {
//                          $start  = Carbon::parse($start)->setTime(0, 0, 0);
//                          $end    = Carbon::parse($end)->setTime(0, 0, 0);
//                      }

                      $form->fill([
                          'start' => $start,
                          'end' => $end,
                          'sig_location_id' => $arguments['newResource']['id'] ?? null,
                      ]);
                  }),
            EditAction::make('view')
                ->modalFooterActions([
                    Action::make(__("Save"))
                        ->submit("form"),
                    Action::make(__("Cancel"))
                        ->color("gray")
                        ->close(),
                    DeleteAction::make()
                        ->outlined()
                        ->size("xs")
                ])
        ];
    }
    public function unmountAction(bool $shouldCancelParentActions = true): void {
        parent::unmountAction($shouldCancelParentActions);
        $this->refreshRecords();
    }

    public function getFormSchema(): array {
        return [

            Grid::make()
                ->columns(2)
                ->schema([
                    Select::make('sig_event_id')
                        ->relationship('sigEvent', 'name')
                        ->prefix(__("SIG"))
                        ->hiddenLabel()
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('sig_location_id')
                        ->prefix(__("Location"))
                        ->hiddenLabel()
                        ->relationship('sigLocation', 'name'),

                    DateTimePicker::make('start')
                        ->seconds(false)
                        ->required()
                        ->prefix(__("Start"))
                        ->hiddenLabel(true),
                    DateTimePicker::make('end')
                        ->seconds(false)
                        ->required()
                        ->prefix(__("End"))
                        ->hiddenLabel(true),

                    Section::make(__("Options"))
                        ->schema([
                            Toggle::make('hide')
                                  ->label(__("Hide Event on Schedule")),
                            Toggle::make('new')
                                  ->label(__("New Event")),
                        ])
                ]),
        ];
    }
    protected static string $view = "vendor.filament-fullcalendar.sig-planner";


}
