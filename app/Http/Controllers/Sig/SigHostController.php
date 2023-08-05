<?php

namespace App\Http\Controllers\Sig;

use \Gate;
use App\Http\Controllers\Controller;
use App\Models\SigHost;
use Illuminate\Http\Request;

class SigHostController extends Controller
{
    public function index() {
        Gate::authorize('manage_hosts');

        $hosts = SigHost::withCount("sigEvents")->get();

        return view("hosts.index", compact("hosts"));
    }

    public function show(SigHost $host) {
        Gate::authorize('manage_hosts');

        return view("hosts.show", [
            'host' => $host,
            'sigs' => $host->sigEvents,
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

        return back()->withSuccess("Angabgen gespeichert!");
    }

    public function destroy(SigHost $host) {
        Gate::authorize('manage_hosts');

        if($host->sigEvents->count() > 0)
            return back()->withErrors("Host hat noch Events eingetragen!");
        $host->delete();
        return redirect(route("hosts.index"))->withSuccess("Host gelöscht!");
    }
}
