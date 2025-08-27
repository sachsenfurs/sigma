<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use App\Models\TimetableEntry;

class ConbookExportController extends Controller
{
    public function index() {
        $this->authorize("deleteAny", SigEvent::class);

        $days = TimetableEntry::public()->noAnnouncements()->with("sigEvent")->orderBy("start")->get()->groupBy(function($item) {
            return $item->start->translatedFormat("l, d.m.Y");
        });

        return view("conbook-export.index", [
            'days' => $days,
        ]);
    }
}
