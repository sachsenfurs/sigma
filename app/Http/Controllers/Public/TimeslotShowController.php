<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TimetableEntry;
use Illuminate\Http\Request;

class TimeslotShowController extends Controller
{
    public function index(TimetableEntry $entry) {
        return view("public.timeslot-show", [
            'entry' => $entry,
        ]);
    }
}
