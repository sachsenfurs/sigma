<?php

namespace App\Http\Controllers\Schedule;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;

class ConbookExportController extends Controller
{
    public function index() {
        $days = TimetableEntry::public()->noAnnouncements()->with("sigEvent")->orderBy("start")->get()->groupBy(function($item) {
            return $item->start->format("d.m.Y");
        });

        return view("conbook-export.index", [
            'days' => $days,
        ]);
    }
}
