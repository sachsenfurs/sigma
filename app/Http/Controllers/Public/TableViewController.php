<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use App\Models\TimetableEntry;
use App\Models\SigLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TableViewController extends Controller
{
    public function index() {
        $entries = TimetableEntry::public()->orderBy("start")->get();
        $days = $entries->pluck("start")->groupBy(function($item) {
            return $item->format("d.m.Y");
        })->keys();
        return view("public.tableview",[
            'days' => $days,
            'entries' => $entries,
            'locations' => SigLocation::withCount("sigEvents")
                                                  ->having("sig_events_count", ">", 0)
                                                  ->groupBy("name")
                                                  ->orderByRaw("FIELD(sig_locations.id, 27,13,11,22,10,5,15,14,2,21,20,19,18,7,6,25,1) DESC")
                                                  ->get(),
        ]);
    }

    public function indexOld() {
        $entries = TimetableEntry::public()->orderBy("start")->get();
        $days = $entries->pluck("start")->groupBy(function($item) {
            return $item->format("d.m.Y");
        })->keys();
        return view("public.tableview-old",[
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
        return TimetableEntry::public()
            ->with("sigEvent", function($query) {
                return $query->with("sigHost")
                    ->with("sigLocation")
                    ->with("sigTags");
            })
            ->with("sigLocation")
            ->orderBy("start")->get();
    }
}
