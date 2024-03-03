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
        if(auth()->user()?->can("manage_hosts"))
            $hosts = SigHost::all();
        else
            $hosts = SigHost::public()->get();

        return view("hosts.index", compact("hosts"));
    }

    public function create(){
        $user = User::where('reg_id', auth()->user()->reg_id)->first();
        return view('hosts.create', compact('user'));

    }
    public function store(Request $request){

        $host = SigHost::create([
            'name' => $request->input('SigHostName'),
            'description' => 'Dies ist ein Test',
            'hide' => '0',
            'reg_id' => $request->input('UserRegID'),
        ]);

        return redirect('sigsignin');

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

    public function edit(SigHost $host) {
        Gate::authorize('manage_hosts');

        return view("hosts.edit", [
            'host' => $host,
        ]);
    }

    public function update(Request $request, SigHost $host) {
        Gate::authorize('manage_hosts');

        $validated = $request->validate([
            'name' => "required|string",
            'description' => "nullable|string",
            'reg_id' => 'nullable|int',
        ]);

        $validated['hide'] = $request->has("hide");

        $host->update($validated);

        return back()->withSuccess("Angaben gespeichert!");
    }

    public function destroy(SigHost $host) {
        Gate::authorize('manage_hosts');

        if($host->sigEvents->count() > 0)
            return back()->withErrors("Host hat noch Events eingetragen!");
        $host->delete();
        return redirect(route("hosts.index"))->withSuccess("Host gelöscht!");
    }
}
