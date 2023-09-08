<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use App\Models\TimetableEntry;
use App\Models\SigLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListViewController extends Controller
{
    public function index() {
        $entries = TimetableEntry::public()->orderBy("start")->get();
        $days = $entries->pluck("start")->groupBy(function($item) {
            return $item->format("d.m.Y");
        })->keys();
        return view("public.listview",[
            'days' => $days,
            'entries' => $entries,
            'locations' => SigLocation::withCount("sigEvents")
                                                  ->having("sig_events_count", ">", 0)
                                                  ->groupBy("name")
                                                  ->orderByRaw("FIELD(sig_locations.id, 27,13,11,22,10,5,15,14,2,21,20,19,18,7,6,25,1) DESC")
                                                  ->get(),
        ]);
    }


    public function timetableIndex() {
        $entries = TimetableEntry::public()
            ->with("sigLocation")
            ->with("sigEvent", function($query) {
                return $query->with("sigHost")
//                    ->without("sigLocation")
                    ->with("sigTags");
            })
            ->orderBy("start")
            ->get();

        // remove unnecessary information
        foreach($entries AS $entry) {
            $entry->sigLocation->setVisible([
                'id',
                'name',
                'description',
                'name_localized'
            ]);
        }
        return $entries;
    }
}
