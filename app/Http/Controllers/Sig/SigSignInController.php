<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigHost;
use App\Models\SigEvent;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Redirect;

class SigSignInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $sighost = SigHost::where('reg_id', $user->reg_id)->first();

        if ($user->reg_id)
        {
            if ($sighost)
            {
                $sigs = SigEvent::where('sig_host_id', $sighost->id)->get();

                $siglocations = [];
                foreach($sigs as $sig) {
                    $time = TimetableEntry::where('sig_event_id', $sig->id)->first();

                    if (!$time)
                    {
                        $siglocations[$sig->id] = [
                            'time' => __('No Time Assigned'),
                            'location' => __('No Location Assigned'),
                        ];
                    }
                    else
                    {
                        $location = SigLocation::where('id', $time->sig_location_id)->first();
                        if (!$location)
                        {
                            $siglocations[$sig->id] = [
                                'time' => $time,
                                'location' => __('No Location Assigned'),
                            ];
                        }
                        else
                        {
                            $siglocations[$sig->id] = [
                                'time' => $time,
                                'location' => $location->name,
                            ];
                        }
                    }
                }

                return view('sigs.signup.index', compact([
                    'user',
                    'sighost',
                    'sigs',
                    'siglocations',
                ]));
            }

            return view('sigs.signup.index', compact([
                'user',
                'sighost',
            ]));
        }
        else
        {
            return redirect('sigs/signup/create')->withErrors("Du hast keine Sigs erstellt!");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('id', auth()->user()->id)->first();

        if ($user->reg_id)
        {
            $sighost = SigHost::where('reg_id', $user->reg_id)->first();
            // dd($user);
            return view('sigs.signup.create', compact(['user','sighost']));
        }
        else{
            return view('sigs.signup.create', compact(['user']));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

        $validated = $request->validate([
            'SigName' => 'required|string',
            'SigDescriptionDE' => 'required|string',
            'additional_infos' => 'required_if:SigNeedsOther,1',
        ], [
            'SigName.required' => __('Please insert a SIG Name!'),
            'SigDescriptionDE.required' => __('Please insert a SIG Description!'),
            'additional_infos.required_if' => __('The Additional Informations field is required when Other Stuff is Yes!'),
        ]);

    if (!SigHost::where('reg_id', $user->reg_id)->first())
    {
        SigHost::create([
            'name' => $user->name,
            'hide' => '0',
            'reg_id' => $user->reg_id,
        ]);
    }

    $languages = []; // Initialisiert das Array für die Sprachen leer

    // Ermitteln der gewünschten Sprachkonfiguration basierend auf der Eingabe
    switch ($request->input('SigLang')) {
        case '0':
            $languages = ["de"]; // Nur Deutsch
            break;
        case '1':
            $languages = ["en"]; // Nur Englisch
            break;
        case '2':
            $languages = ["de", "en"]; // Deutsch und Englisch
            break;
            default:
            $languages = []; // Falls keine gültige Option ausgewählt wurde, bleibt das Array leer
            break;
        }

    // dd($request);
    $event = SigEvent::create([
        'name' => $validated['SigName'],
        'sig_host_id' => SigHost::where('reg_id', $user->reg_id)->first()->id,
        'languages' => $languages, // Verwendet das modifizierte $languages Array
        'description' => $request->input('SigDescriptionDE'),
        'sig_location_id' => '2',
        'reg_possible' => '0',
        'max_regs_per_day' => '1',
        'fursuit_support' => $request->input('SigNeedsFurrySupport'),
        'medic' => $request->input('SigNeedsMedic'),
        'security' => $request->input('SigNeedsSecu'),
        'other_stuff' => $request->input('SigNeedsOther'),
        'additional_infos' => $request->input('additional_infos'),
    ]);
    // dd($event);


    return redirect('sigs/signup');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
