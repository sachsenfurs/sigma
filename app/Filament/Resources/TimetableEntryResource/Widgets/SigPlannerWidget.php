<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Enums\Approval;
use App\Filament\Resources\TimetableEntryResource;
use App\Filament\Resources\TimetableEntryResource\Pages\CreateTimetableEntry;
use App\Filament\Resources\TimetableEntryResource\Pages\EditTimetableEntry;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class SigPlannerWidget extends FullCalendarWidget
{
    public Model | string | null $model = TimetableEntry::class;

    protected static string $view = "vendor.filament-fullcalendar.sig-planner";

    public array $resources = [];

    public function mount(): void {
        $this->resources = SigLocation::select(["id", "name AS title", "description"])->orderBy("name")->get()->toArray();
    }

    public function fetchEvents(array $info): array {
        return TimetableEntry::query()
             ->where(function($query) use ($info) {
                 $query->where('start', '>=', $info['start'])
                       ->where('start', '<=', $info['end']);
             })
             ->orWhere('end', '>=', $info['start'])
             ->with("sigEvent")
             ->with("sigEvent.sigHosts")
             ->get()
             ->map(
                fn (TimetableEntry $entry) => EventData::make()
                  ->id($entry->id)
                  ->title($entry->sigEvent->name)
                  ->start($entry->start->toDateTimeLocalString())
                  ->end($entry->end->toDateTimeLocalString())
                  ->resourceId($entry->sigLocation->id)
                  ->borderColor((function() use ($entry) {
                      if($entry->approval != Approval::APPROVED)
                          return "#550011";
                      if($entry->cancelled)
                          return "#ba5334";
                      if($entry->hide)
                          return "#756d6a";
                      if($entry->has_time_changed || $entry->has_location_changed)
                          return "#ff0000";
                      if($entry->sigEvent->is_private)
                          return "#380550";

                      if($entry->sigEvent->primaryHost?->color)
                        return "#ffffff00";

                      return "";
                  })())
                  ->extraProperties([
                      'backgroundColor' => (function() use ($entry) {
                          if($entry->approval != Approval::APPROVED)
                              return "#ff0000";
                          if($entry->cancelled)
                              return "#eb8060";
                          if($entry->hide)
                              return "#948e8a";
                          if($entry->sigEvent->is_private)
                              return "#6e0b9d";

                          if($entry->sigEvent->primaryHost?->color)
                              return $entry->sigEvent->primaryHost?->color;

                          return "";
                      })(),
                  ])
             )
             ->toArray();
    }

    protected function headerActions(): array {
        return [
            CreateTimetableEntry::getCreateAction(CreateAction::make())
                ->modelLabel(TimetableEntryResource::getModelLabel()),
        ];
    }

    #[On('refresh')]
    public function refresh(): void {
        $this->refreshRecords();
    }

    public function getFormSchema(): array {
        return EditTimetableEntry::getSchema();
    }

    protected function modalActions(): array {
        return [
            EditAction::make("edit")
                ->authorize("update", TimetableEntry::class)
                ->using(function(Model $record, array $data) {
                    return EditTimetableEntry::handleUpdate($record, $data);
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
                ->modelLabel(TimetableEntryResource::getModelLabel())
                ->modalFooterActionsAlignment(Alignment::End),
            EditAction::make("view")
                ->authorize("update", TimetableEntry::class)
                ->using(function(Model $record, array $data) {
                    return EditTimetableEntry::handleUpdate($record, $data);
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
                ->modelLabel(TimetableEntryResource::getModelLabel())
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
    public function unmountAction(bool $shouldCancelParentActions = true, bool $shouldCloseModal = true): void {
        parent::unmountAction($shouldCancelParentActions, $shouldCloseModal);
        $this->refreshRecords();
    }


//    /**
//     * Override from InteractsWithEvents to set the default clickAction to "edit"
//     * @param array $event
//     * @return void
//     */
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

}
