<?php

namespace App\Providers\Filament;

use App\Models\SigLocation;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Filament\Panel;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;


class FilamentSigCalendarProvider {
    public static function registerPlugin(Panel $panel)  {
        $panel->plugin(
            FilamentFullCalendarPlugin::make()
              ->schedulerLicenseKey("CC-Attribution-NonCommercial-NoDerivatives")
              ->selectable(true)
              ->editable(true)
              ->plugins([
                  'resourceTimeGrid',
                  'resourceTimeline',
                  'interaction'
              ])
              ->config([
                  'initialView' => "resourceTimeGridDay",
                  'resources' => SigLocation::used()->get()->map(function($l) {
                      $l->title = $l->description_localized;
//                      if($l->description != $l->name)
//                          $l->title .= " (" . $l->description_localized . ")";
                      return $l;
                  })->toArray(),
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
                  'slotMaxTime' => "32:00:00",
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
                      return app(AppSettings::class)->event_start->format("Y-m-d");
                  })(),
              ])
        );
    }
}
