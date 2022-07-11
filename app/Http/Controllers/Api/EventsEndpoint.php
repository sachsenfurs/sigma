<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimeTableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventsEndpoint extends Controller
{
    public function index(Request $request) {

        $events = [];
        foreach(TimeTableEntry::public()->get() AS $entry) {
            /**
             * @var TimeTableEntry $entry
             */
            $event = [
                'name'                  => $entry->sigEvent->name,
                'name_en'               => "-english here-",
                'host'                  => $entry->sigEvent->sigHost->name,
                'start'                 => Carbon::parse($entry->start)->toW3cString(),
                'end'                   => Carbon::parse($entry->end)->toW3cString(),
                'description'           => $entry->sigEvent->description,
                'description_en'        => "-english desc here-",
                'short_description'     => false,
                'short_description_en'  => false,
                'languages'             => $entry->sigEvent->languages,
                'location'              => $entry->sigLocation->name ?? "Null",
                'location_ids'          => $entry->sigLocation->render_ids ?? [],
                'time_changed'          => $entry->parentEntry && $entry->parentEntry->start != $entry->start,
                'location_changed'      => $entry->parentEntry && $entry->parentEntry->sigLocaton != $entry->sigLocation,
                'cancelled'             => $entry->cancelled,
            ];

            $events[] = $event;
        }
        return $events;
    }
}
