<?php

namespace App\Models\Traits;

use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Illuminate\Database\Eloquent\Builder;

trait HasTimetableEntries {

    public function timetableEntries() {

        return $this->hasMany(TimetableEntry::class)
            ->orderBy("start");
//        // TODO:
//        // das hier geht bestimmt auch noch schöner >.>
//        $relation = $this->hasMany(TimetableEntry::class)
//                         ->join("sig_events", "sig_events.id", "=", "timetable_entries.sig_event_id")
//                         ->select("timetable_entries.*");
//        if($this instanceof SigLocation)
//                 $relation = $relation->orWhere("timetable_entries.sig_location_id", $this->id);
//
//        return $relation->orderBy("start", "ASC");
//
////        if($this instanceof SigEvent)
////            $relation = $this->hasMany(TimetableEntry::class);

    }

}
