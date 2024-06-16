<?php

namespace App\Http\Controllers\Sig;

use App\Models\User;
use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigHost;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SigHostController extends Controller
{
    public function index() {
        $hosts = [];
        if(Gate::allows("viewAny", SigHost::class))
            $hosts = SigHost::public()->get();

        return view("hosts.index", compact("hosts"));
    }

    public function show(SigHost $host) {
        $events = $host->sigEvents()
            ->with("timetableEntries")
            ->get()
            ->sortBy(function($event, $key) {
                return ($event->timetableEntries->first()?->start);
            });

        return view("hosts.show", [
            'host' => $host,
            'sigs' => $events,
        ]);
    }

}
