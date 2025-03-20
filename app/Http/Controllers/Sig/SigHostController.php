<?php

namespace App\Http\Controllers\Sig;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigHost;

class SigHostController extends Controller
{
    public function index() {
        $hosts = [];
        if(Gate::allows("viewAny", SigHost::class))
            $hosts = SigHost::with(["sigEvents" => fn($query) => $query->public(), "user", "sigEvents.timetableEntries"])
                            ->public()
                            ->orderBy("name")->get();

        return view("hosts.index", compact("hosts"));
    }

    public function show(SigHost $host) {
        $this->authorize("view", $host);

        $sigEvents = $host->sigEvents()
            ->public()
            ->with("sigHosts")
            ->get()
            ->sortBy(function($event, $key) {
                return ($event->timetableEntries->first()?->start);
            });

        return view("hosts.show", [
            'host' => $host,
            'sigEvents' => $sigEvents,
        ]);
    }

}
