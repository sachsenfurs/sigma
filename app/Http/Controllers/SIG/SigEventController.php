<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTranslation;
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

        $locations = SigLocation::all();

        return view("sigs.create", compact([
            'hosts',
            'locations'
        ]));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => "required|string",
            'name_en' => "required|string",
            'host' => "required|string",
            'location' => 'required|exists:' . SigLocation::class . ",id",
            'description' => "string",
            'description_en' => "string",
        ]);

        if(SigHost::where("name", $validated['host'])->exists()) {
            $host_id = SigHost::where("name", $validated['host'])->first();
        } else {
            $host_id = SigHost::create([
                'name' => $validated['host'],
            ]);
        }


        $languages = [];
        if($request->get("lang_de"))
            $languages[] = "de";
        if($request->get("lang_en")) {
            $languages[] = "en";

        }

        $sig = new SigEvent();
        $sig->name = $validated['name'];
        $sig->sigHost()->associate($host_id);
        $sig->sigLocation()->associate($validated['location']);
        $sig->description = $validated['description'];
        $sig->languages = $languages;
        $sig->save();

        if(in_array("en", $languages)){
            $translate = new SigTranslation([
                'language' => "en",
                'name' => $validated['name_en'],
                'description' => $validated['description_en'],
            ]);
            $translate->sigEvent()->associate($sig);
            $translate->save();

        }
        return back()->withErrors("ff")->withInput();
    }
}
