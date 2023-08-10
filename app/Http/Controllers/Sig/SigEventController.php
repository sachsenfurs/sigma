<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigAttendee;
use App\Models\SigEvent;
use App\Models\SigFavorite;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class SigEventController extends Controller
{
    public function index() {
        $this->authorize('viewAny', SigEvent::class);

        $sigs   = SigEvent::withCount("TimetableEntries")->orderBy("timetable_entries_count", "ASC")->get();
        return view("sigs.index", compact("sigs"));
    }

    public function show(SigEvent $sig) {
        $this->authorize('view', $sig);

        $additionalInformations = [];
        $favs = 0;
        foreach($sig->timetableEntries as $entry) {
            $favs = $favs + SigFavorite::where('timetable_entry_id', $entry->id)->count();
            $timeslots = [];
            foreach($entry->sigTimeslots as $timeslot) {
                $timeslots[$timeslot->id] = SigAttendee::where('sig_timeslot_id', $timeslot->id)->get();
            }
            $additionalInformations[$entry->id] = [
                'favorites' => $favs,
                'timeslots' => $timeslots
            ];
        }

        return view("sigs.show", compact([
            'sig',
            'additionalInformations',
        ]));
    }

    public function edit(SigEvent $sig) {
        $this->authorize('update', $sig);

        $host_names      = SigHost::pluck("name")->all();
        $locations  = SigLocation::orderBy("name")->get();
        $hosts = SigHost::all();
        return view("sigs.createEdit", compact([
            'sig',
            'hosts',
            'host_names',
            'locations'
        ]));
    }

    public function create() {
        $this->authorize('create', SigEvent::class);

        $host_names = SigHost::pluck("name")->all();
        $locations = SigLocation::orderBy("name")->get();
        $hosts = SigHost::all();

        return view("sigs.createEdit", compact([
            'host_names',
            'hosts',
            'locations'
        ]));
    }

    public function store(Request $request) {
        $this->authorize('create', SigEvent::class);

        $validated = $request->validate([
            'name' => "required|string|unique:" . SigEvent::class . ",name",
            'name_en' => "required|string",
            'host_id' => 'required',
            'host' => "required_if:host_id,NEW|string",
            'reg_id' => 'nullable|int',
            'location' => 'required|exists:' . SigLocation::class . ",id",
            'description' => "nullable|string",
            'description_en' => "nullable|string",
            'reg_possible' => '',
            'max_regs_per_day' => 'nullable|integer',
            'date-start' => "array",
            'date-end' => "array",
            'date-start.*' => 'date',
            'date-end.*' => 'date',
        ]);

        $host_id = $request->input('host_id');
        if($host_id == 'NEW') {
            // Does not exist
            $host_id = SigHost::create([
                'name' => $validated['host'],
                'reg_id' => $validated['reg_id'],
            ]);
        } else {
            // Does exist
            $host_id = SigHost::whereId($host_id)->first()->id;
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
        if ($request->has('reg_possible')) {
			$sig->reg_possible = true;
		} else {
            $sig->reg_possible = false;
        }
        if ($validated['max_regs_per_day']) {
            $sig->max_regs_per_day = $validated['max_regs_per_day'];
        }
        $sig->save();

        // Insert translation
        //if(in_array("en", $languages)){
            $translate = new SigTranslation([
                'language' => "en",
                'name' => $validated['name_en'],
                'description' => $validated['description_en'] ?? "",
            ]);
            $translate->sigEvent()->associate($sig);
            $translate->save();
        //}

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
        return redirect(route("sigs.index"))->withSuccess("SIG erstellt");
    }

    public function update(Request $request, SigEvent $sig) {
        $this->authorize('update', $sig);

        $validated = $request->validate([
            'name' => "required|string",
            'name_en' => "required|string",
            'host_id' => 'exclude_if:host_id,NEW|required|exists:sig_hosts,id',
            'host' => "required_if:host_id,NEW|string",
            'reg_id' => 'integer|nullable',
            'max_regs_per_day' => 'nullable|integer',
            'location' => 'required|exists:' . SigLocation::class . ",id",
            'description' => "nullable|string",
            'description_en' => "nullable|string",
        ]);
        $languages = [];
        if($request->has("lang_de"))
            $languages[] = "de";
        if($request->has("lang_en")) {
            $languages[] = "en";
        }
        $host_id = $request->get('host_id');
        if($host_id == 'NEW') {
            // Does not exist
            $host_id = SigHost::create([
                'name' => $validated['host'],
                'reg_id' => $validated['reg_id'],
            ]);
        } else {
            // Does exist
            $host_id = SigHost::whereId($host_id)->first()->id;
        }

        $sig->name = $validated['name'];
        $sig->description = $validated['description'];
        $sig->sigLocation()->associate($validated['location']);
        $sig->languages = $languages;
        $sig->sigHost()->associate($host_id);
        $sig->reg_possible = $request->has('reg_possible');
        $sig->max_regs_per_day = $validated['max_regs_per_day'] ?? $sig->max_regs_per_day;

        if(!$sig->sigTranslation) {
            $sig->sigTranslation()->create([
                'language' => 'en',
                'name' => $validated['name_en'],
                'description' => $validated['description_en'],
            ]);
        } else {
            $sig->sigTranslation->name = $validated['name_en'];
            $sig->sigTranslation->description = $validated['description_en'];
        }

        $sig->save();

        return back()->withSuccess("Änderungen gespeichert");
    }

    public function destroy(SigEvent $sig) {
        $this->authorize('delete', $sig);

        $sig->delete();
        return redirect(route("sigs.index"))->withSuccess("SIG gelöscht");
    }

}
