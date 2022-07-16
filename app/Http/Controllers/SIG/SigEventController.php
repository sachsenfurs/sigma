<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SigEventController extends Controller
{
    public function index() {
        $sigs   = SigEvent::withCount("TimetableEntries")->orderBy("timetable_entries_count", "ASC")->get();

        return view("sigs.index", compact("sigs"));
    }

    public function show(SigEvent $sig) {
        $hosts      = SigHost::pluck("name")->all();
        $locations  = SigLocation::all();
        return view("sigs.createEdit", compact([
            'sig',
            'hosts',
            'locations'
        ]));
    }

    public function create() {
        $hosts = SigHost::pluck("name")->all();
        $locations = SigLocation::all();

        return view("sigs.createEdit", compact([
            'hosts',
            'locations'
        ]));
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'name' => "required|string|unique:" . SigEvent::class . ",name",
            'name_en' => "required|string",
            'host' => "required|string",
            'location' => 'required|exists:' . SigLocation::class . ",id",
            'description' => "string",
            'description_en' => "nullable|string",
            'date-start' => "array",
            'date-end' => "array",
            'date-start.*' => 'date',
            'date-end.*' => 'date',
        ]);


        if(SigHost::where("name", $validated['host'])->exists()) {
            $host_id = SigHost::where("name", $validated['host'])->first();
        } else {
            $host_id = SigHost::create([
                'name' => $validated['host'],
            ]);
        }

        $languages = [];
        if($request->has("lang_de"))
            $languages[] = "de";
        if($request->has("lang_en")) {
            $languages[] = "en";
        }

        $sig = new SigEvent();
        $sig->name = $validated['name'];
        $sig->sigHost()->associate($host_id);
        $sig->sigLocation()->associate($validated['location']);
        $sig->description = $validated['description'];
        $sig->languages = $languages;
        $sig->save();

        // Insert translation
        if(in_array("en", $languages)){
            $translate = new SigTranslation([
                'language' => "en",
                'name' => $validated['name_en'],
                'description' => $validated['description_en'],
            ]);
            $translate->sigEvent()->associate($sig);
            $translate->save();
        }

        // insert in timetable (if set)
        if(is_array($request->get("date-start"))) {

            foreach($request->get("date-start") AS $i=>$dateStart) {
                $dateTimeStart = Carbon::parse($dateStart);
                $dateTimeEnd = Carbon::parse($request->get("date-end")[$i]);

                $sig->timeTableEntries()->create([
                    'start' => $dateTimeStart,
                    'end' => $dateTimeEnd,
                    'hide' => $request->boolean("hide"),
                ]);
            }
        }
        return redirect(route("sigs.create"))->withSuccess("SIG erstellt");
    }

    public function update(Request $request, SigEvent $sig) {
        $validated = $request->validate([
            'name' => "required|string",
            'name_en' => "required|string",
            'host' => "string",
            'location' => 'required|exists:' . SigLocation::class . ",id",
            'description' => "string",
            'description_en' => "nullable|string",
        ]);
        $languages = [];
        if($request->has("lang_de"))
            $languages[] = "de";
        if($request->has("lang_en")) {
            $languages[] = "en";
        }

        if(SigHost::where("name", $validated['host'])->exists()) {
            $host_id = SigHost::where("name", $validated['host'])->first();
        } else {
            $host_id = SigHost::create([
                'name' => $validated['host'],
            ]);
        }
        unset($validated['host']);

        $sig->sigLocation()->associate($validated['location']);
        unset($validated['location']);

        $sig->update($validated);
        $sig->languages = $languages;
        $sig->sigHost()->associate($host_id);
        $sig->save();
        return back()->withSuccess("Änderungen gespeichert");
    }

    public function destroy(SigEvent $sig) {
        $sig->delete();
        return redirect(route("sigs.index"))->withSuccess("SIG gelöscht");
    }

}
