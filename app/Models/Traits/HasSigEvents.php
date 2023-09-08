<?php

namespace App\Models\Traits;

use App\Models\SigEvent;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Illuminate\Database\Query\Builder;

trait HasSigEvents {

    public function sigEvents() {
        return $this->hasMany(SigEvent::class)
            ->orderByRaw("
             (
                SELECT START FROM timetable_entries
                WHERE timetable_entries.sig_event_id = sig_events.id
                ORDER BY start
                LIMIT 1
             )
        ");
//        // das hier geht bestimmt noch schöner >.>
//        if($this instanceof SigLocation) {
//
//            $relation = $this->hasMany(SigEvent::class)
//                ->join("timetable_entries", "sig_events.id", "=", "timetable_entries.sig_event_id")
//                ->where(function ($query) {
//                    $query->where("timetable_entries.sig_location_id", $this->id);
//                })
//                ->select("sig_events.*")
//            ;
//        }
//        return $relation;
//            $sigEvents = $this->hasMany(SigEvent::class)->select(["sig_events.*", "sig_location_id"]);
//            $events = $this->hasManyThrough(TimetableEntry::class, SigEvent::class)->select(["sig_events.*","timetable_entries.sig_location_id"]);
//            return $sigEvents->union($events);
    }

    public function getPublicSigEventCount() {
        return $this->sigEvents()
                    ->with("timetableEntries")
                    ->get()
                    ->filter(function($sigEvent) {
                        return !$sigEvent->isCompletelyPrivate();
                    })->count();
    }

}
