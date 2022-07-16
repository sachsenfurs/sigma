<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventsEndpoint extends Controller
{
    public function index(Request $request) {

        $events = [];
        foreach(TimetableEntry::public()->orderBy("start", "ASC")->get() AS $entry) {
            /**
             * @var TimetableEntry $entry
             */
            $event = [
                'name'                  => $entry->sigEvent->name,
                'name_en'               => $entry->sigEvent->name_en,
                'host'                  => $entry->sigEvent->sigHost->name,
                'start'                 => Carbon::parse($entry->start)->toW3cString(),
                'end'                   => Carbon::parse($entry->end)->toW3cString(),
                'description'           => $entry->sigEvent->description,
                'description_en'        => $entry->sigEvent->description_en,
                'short_description'     => false,
                'short_description_en'  => false,
                'languages'             => $entry->sigEvent->languages,
                'location'              => $entry->sigLocation->name ?? "Null",
                'location_ids'          => $entry->sigLocation->render_ids ?? [],
                'time_changed'          => ($entry->parentEntry && $entry->parentEntry->start != $entry->start) || $entry->updated_at > $entry->created_at,
                'location_changed'      => $entry->parentEntry && $entry->parentEntry->sigLocaton != $entry->sigLocation,
                'cancelled'             => $entry->cancelled,
            ];

            $events[] = $event;
        }
        return $events;
    }
}