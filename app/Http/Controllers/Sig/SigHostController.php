<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigHost;
use Illuminate\Http\Request;

class SigHostController extends Controller
{
    public function index() {
        $hosts = SigHost::withCount("sigEvents")->get();

        return view("hosts.index", compact("hosts"));
    }

    public function show(SigHost $host) {
        return view("hosts.show", [
            'host' => $host,
            'sigs' => $host->sigEvents,
        ]);
    }

    public function edit(SigHost $host) {
        return view("hosts.edit", [
            'host' => $host,
        ]);
    }

    public function update(Request $request, SigHost $host) {
        $validated = $request->validate([
            'name' => "required|string",
            'description' => "nullable|string",
        ]);

        $host->name = $validated['name'];
        $host->hide = $request->has("hide");
        $host->description = $validated['description'] ?? "";
        $host->save();

        return back()->withSuccess("Angabgen gespeichert!");
    }

    public function destroy(SigHost $host) {
        if($host->sigEvents->count() > 0)
            return back()->withErrors("Host hat noch Events eingetragen!");
        $host->delete();
        return redirect(route("hosts.index"))->withSuccess("Host gelöscht!");
    }
}
