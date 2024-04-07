<?php

namespace App\Providers\Filament;

use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;


class FilamentFullCalendarProvider {
    public static function registerPlugin(Panel $panel)  {
        $panel->plugin(
            FilamentFullCalendarPlugin::make()
              ->schedulerLicenseKey("CC-Attribution-NonCommercial-NoDerivatives")
              ->selectable(true)
              ->editable(true)
              ->plugins([
                  'resourceTimeGrid',
                  'resourceTimeline',
              ])
              ->config([
                  'initialView' => "resourceTimeGridDay",
                  'resources' => SigLocation::select("id", "name AS title", "show_default")->where("show_default", true)->get()->toArray(),
                  'headerToolbar' => [
                      'left' => 'prev,next,today',
                      'center' => 'title',
                      'right' => 'resourceTimeGridDay,resourceTimeline,dayGridMonth'
                  ],
                  'titleFormat' => [
                      'day' => 'numeric',
                      'month' => 'long',
                      'weekday' => 'long',
                  ],
                  'nowIndicator' => true,
                  'slotMinTime' => "08:00:00",
                  'slotMaxTime' => "28:00:00",
                  'eventResizableFromStart' => true,
                  'allDaySlot' => false,
                  'showNonCurrentDates' => true,
                  'defaultTimedEventDuration' => "01:00",
                  'forceEventDuration' => true,
                  'scrollTimeReset' => false,
                  'height' => '150vh',
                  'expandRows' => true,
                  'stickyHeaderDates' => true,
                  'contentHeight' => "auto",
                  'initialDate' => (function() {
                      $first = TimetableEntry::orderBy('start')->first();
                      if(Carbon::parse($first?->start)->isAfter(Carbon::now()))
                          return $first->start->format("Y-m-d");
                      return Carbon::now()->format("Y-m-d");
                  })(),
              ])
        );
    }
}
