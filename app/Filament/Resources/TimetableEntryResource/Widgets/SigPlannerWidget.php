<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Filament\Resources\TimetableEntryResource;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Filament\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class SigPlannerWidget extends FullCalendarWidget
{
    public Model | string | null $model = TimetableEntry::class;

    protected static string $view = "vendor.filament-fullcalendar.sig-planner";

    public $resources = [];

    public function mount() {
        $this->resources = SigLocation::select(["id", "name AS title", "description"])->orderBy("name")->get()->toArray();
    }

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
                  ->resourceId($entry->sigLocation->id)
                  ->borderColor((function() use ($entry) {
                      if($entry->cancelled)
                          return "#ba5334";
                      if($entry->hide)
                          return "#756d6a";
                      if($entry->has_time_changed || $entry->has_location_changed)
                          return "#ff0000";
                      return "";
                  })())
                  ->extraProperties([
                      'backgroundColor' => (function() use ($entry) {
                          if($entry->cancelled)
                              return "#eb8060";
                          if($entry->hide)
                              return "#948e8a";

                          return "";
                      })(),
                  ])
             )
             ->toArray();
    }

    protected function headerActions(): array {
        return [
            CreateAction::make()
                ->mountUsing(function(Form $form, array $arguments) {
                    $form->fill([
                        'entries' => [
                            [
                                'start' => $arguments['start'] ?? null,
                                'end' => $arguments['end'] ?? null,
                            ]
                        ],
                        'sig_location_id' => $arguments['resource']['id'] ?? null,
                    ]);
                })
                ->mutateFormDataUsing(fn($data) => TimetableEntryResource\Pages\CreateTimetableEntry::mutateData($data))
                ->modalFooterActionsAlignment(Alignment::End)
                ->createAnother(false)
                ->form(TimetableEntryResource\Pages\CreateTimetableEntry::getSchema())
            ,
        ];
    }

    protected function modalActions(): array {
        return [
            EditAction::make("edit")
                ->using(function(Model $record, array $data) {
                    return TimetableEntryResource\Pages\EditTimetableEntry::handleUpdate($record, $data);
                })
                ->mountUsing(function(TimetableEntry $entry, Form $form, array $arguments) {
                    $entry->start             = $arguments['event']['start'] ?? $entry->start;
                    $entry->end               = $arguments['event']['end'] ?? $entry->end;
                    $entry->sig_location_id   = $arguments['newResource']['id'] ?? $entry->sig_location_id;

                    $form->fill($entry->attributesToArray());
                })
                ->modalFooterActions([
                    Action::make(__("Save"))
                        ->submit("form")
                        ->keyBindings(["return"]),
                    Action::make(__("Cancel"))
                        ->color("gray")
                        ->close(),
                ])
                ->modalFooterActionsAlignment(Alignment::End)
            ,
            EditAction::make("view")
                ->using(function(Model $record, array $data) {
                    return TimetableEntryResource\Pages\EditTimetableEntry::handleUpdate($record, $data);
                })
                ->modalFooterActions([
                    Action::make(__("Save"))
                        ->submit("form")
                        ->keyBindings(["return"]),
                    Action::make(__("Cancel"))
                        ->color("gray")
                        ->close(),
                    DeleteAction::make()
                        ->outlined()
                ])
                ->modalFooterActionsAlignment(Alignment::End)
        ];
    }

//    /**
//     * "Fast Mode" without confirmation dialog
//     * @param string $name
//     * @param array $arguments
//     * @return mixed
//     */
//    public function mountAction(string $name, array $arguments = []): mixed {
//        // TODO: Implement toggle switch for "fast mode"
//        if($name == "edit") {
//            $entry = TimetableEntry::findOrFail($arguments['event']['id'] ?? null);
//            $entry->start = $arguments['event']['start'] ?? $entry->start;
//            $entry->end = $arguments['event']['end'] ?? $entry->end;
//            $entry->sig_location_id = $arguments['newResource']['id'] ?? $entry->sig_location_id;
//            $entry->save();
//            $this->refreshRecords();
//            return true;
//        } else {
//            return parent::mountAction($name, $arguments);
//        }
//    }

    /**
     * Override from InteractsWithActions to refresh/revert the view if unsuccessful
     * @param bool $shouldCancelParentActions
     * @return void
     */
    public function unmountAction(bool $shouldCancelParentActions = true): void {
        parent::unmountAction($shouldCancelParentActions);
        $this->refreshRecords();
    }


    /**
     * Override from InteractsWithEvents to set the default clickAction to "edit"
     * @param array $event
     * @return void
     */
//    public function onEventClick(array $event): void {
//        if ($this->getModel()) {
//            $this->record = $this->resolveRecord($event['id']);
//        }
//
//
//        $this->replaceMountedAction('edit', [
//            'type' => 'click',
//            'event' => $event,
//        ]);
//    }

//    public function getFormSchema(): array {
//        return [
//            Grid::make()
//            ->columns(2)
//            ->schema(TimetableEntryResource::getSchema())
//        ];
//    }

    public function getFormSchema(): array {
        return //TimetableEntryResource::getSchema();
            [

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
                ])
            ->columns(2)
            ->schema(TimetableEntryResource::getSchema())
        ];
    }

}
