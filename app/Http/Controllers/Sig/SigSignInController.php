<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigTimeslot;
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
                
                    $location = SigLocation::where('id', $time->sig_location_id)->first();
                
                    $siglocations[$sig->id] = [
                        'location' => $location,
                        'time' => $time
                    ];
                }

                return view('sigs.sigsignin.index', compact([
                    'user',
                    'sighost',
                    'sigs',
                    'siglocations',
                ]));
            }

            return view('sigs.sigsignin.index', compact([
                'user',
                'sighost',
            ]));
        }
        else 
        {
            return redirect('sigsignin/create')->withErrors("Du hast keine Sigs erstellt!");
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
            return view('sigs.sigsignin.create', compact(['user','sighost']));
        }
        else{
            return view('sigs.sigsignin.create', compact(['user']));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $host = SigHost::create([
            'name' => $request->input('SigHostName'),
            'description' => 'Dies ist ein Test',
            'hide' => '0',
            'reg_id' => $request->input('UserRegID'),
        ]);

        // dd($host);

        return redirect('sigsignin');
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
