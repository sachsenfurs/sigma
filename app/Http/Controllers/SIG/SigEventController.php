<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use App\Models\SigHost;
use Illuminate\Http\Request;

class SigEventController extends Controller
{
    public function index() {
        $sigs   = SigEvent::withCount("TimeTableEntries")->orderBy("time_table_entries_count", "ASC")->get();

        return view("sigs.index", compact("sigs")) ;
    }

    public function show(SigEvent $sig) {
        return view("sigs.edit", compact("sig"));
    }

    public function create() {
        $hosts = SigHost::pluck("name")->all();

        return view("sigs.create", compact("hosts"));
    }

    public function store(Request $request) {
        return back()->withErrors("ff")->withInput();
    }
}
