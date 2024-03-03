<?php

namespace App\Filament\Resources\TimetableEntryResource\Widgets;

use App\Filament\Resources\TimetableEntryResource;
use App\Models\TimetableEntry;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
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
                  ->extraProperties([
                      'resourceId' => $entry->sigLocation->id ? $entry->sigLocation->id : 11,
                      'backgroundColor' => (function() use ($entry) {
                          if($entry->cancelled)
                              return "#eb8060";
                          if($entry->hide)
                              return "#948e8a";

                          return "";
                      })(),
                      'borderColor' => (function() use ($entry) {
                          if($entry->cancelled)
                              return "#ba5334";
                          if($entry->hide)
                              return "#756d6a";
                          if($entry->has_time_changed || $entry->has_location_changed)
                              return "#ff0000";
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
                      $entry->start             = $arguments['event']['start'] ?? $entry->start;
                      $entry->end               = $arguments['event']['end'] ?? $entry->end;
                      $entry->sig_location_id   = $arguments['newResource']['id'] ?? $entry->sig_location_id;
                      $form->fill($entry->attributesToArray());
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
    public function onEventClick(array $event): void {
        if ($this->getModel()) {
            $this->record = $this->resolveRecord($event['id']);
        }

        $this->mountAction('edit', [
            'type' => 'click',
            'event' => $event,
        ]);
    }

    public function getFormSchema(): array {
        return [
            Grid::make()
            ->columns(2)
            ->schema(TimetableEntryResource::getSchema())
        ];
    }

}
