<?php

namespace App\Http\Controllers\Sig;

use App\Events\Sig\SigApplicationSubmitted;
use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use App\Settings\AppSettings;
use Illuminate\Http\Request;

class SigEventController extends Controller
{

    public function create() {
        if(app(AppSettings::class)->sig_application_deadline->isBefore(now()) && !app(AppSettings::class)->accept_sigs_after_deadline) {
            return redirect()->back()->withError(__("SIG applications are no longer possible"));
        }
        return view("sigs.create");
    }

    public function index() {
        $sigHosts = auth()->user()->sigHosts()->with([
            "sigEvents.timetableEntries",
            "sigEvents.timetableEntries.sigLocation" ,
            "sigEvents" => fn($query) => $query->withCount("favorites"),
        ])->get();
        return view("sigs.index", compact("sigHosts"));
    }


    public function store(Request $request) {
        $this->authorize("create", [SigEvent::class, $request->get("sig_host_id")] );
        $validated = $request->validate([
            'name' => "required|string|min:3|max:65",
            'duration' => "required|int|min:30|max:360",
            'description' => "required|string|min:0|max:3000",
            'additional_info' => "nullable|string|max:3000",
            'languages' => "nullable|array|in:de,en",
        ]);

        $sig = auth()->user()->sigHosts()->firstOrFail()->sigEvents()->create($validated);

        SigApplicationSubmitted::dispatch($sig);

        return redirect(route("sigs.index"))->withSuccess(__("SIG application sent!"));
    }
}
