<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SigEventController extends Controller
{

    public function create() {
        return view("sigs.create");
    }

    public function index() {
        return view("sigs.index");
    }


    public function store(Request $request) {
        $this->authorize("create", [SigEvent::class, $request->get("sig_host_id")] );
        $validated = $request->validate([
            'name' => "required|string|min:3|max:65",
            'duration' => "required|int|min:30|max:360",
            'description' => "required|string|min:0|max:3000",
            'additional_info' => "nullable|string|max:3000",
            'languages' => "nullable|array|in:de,en",
            'sig_host_id' => [
                'required',
                Rule::in(auth()->user()->sigHosts()->pluck("id"))
            ],
        ]);

        $sigEvent = new SigEvent($validated);
        $sigEvent->save();
        $sigEvent->sigHosts()->attach($validated["sig_host_id"]);

        return redirect(route("sigs.index"))->withSuccess(__("SIG application sent!"));
    }
}
