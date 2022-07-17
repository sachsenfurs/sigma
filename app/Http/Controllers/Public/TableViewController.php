<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TableViewController extends Controller
{
    public function index() {
        $entries = \App\Models\TimetableEntry::all();
        $days = $entries->pluck("start")->groupBy(function($item) {
            return $item->format("d.m.Y");
        })->keys();
        return view("public.tableview",[
            'days' => $days,
            'entries' => \App\Models\TimetableEntry::all(),
            'locations' => \App\Models\SigLocation::withCount("sigEvents")
                                                  ->having("sig_events_count", ">", 0)
                                                  ->groupBy("name")
                                                  ->orderByRaw("FIELD(sig_locations.id, 27,13,11,22,10,5,15,14,2,21,20,19,18,7,6,25,1) DESC")
                                                  ->get(),
        ]);
    }
}
