<?php

namespace App\Http\Controllers\DDAS;

use App\Http\Controllers\Controller;
use App\Models\DDAS\DealerTag;
use App\Models\DDAS\Dealer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DealersDenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $dealers = Dealer::all();
        return view('DDAS.dealersden.index', compact('dealers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $tags = DealerTag::all();
        return view('DDAS.dealersden.create', compact('users', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $user = User::where('id', auth()->user()->id)->first();

    // Grundlegende Validierung hinzufügen
    $validate = $request->validate([
        'DealerName' => 'required|string|max:255',
        'DealerSort' => 'required|string|max:6000',
        'DealerGalerie' => 'nullable|url',
        'DealerLogo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        'DealerContactType' => ['required', 'string', 'in:telegram,phone,email'],
        'DealerContact' => 'required|string',
        'DealerSpace' => 'required',
    ]);

    // Bedingte Validierung basierend auf 'DealerContactType'
    Validator::make($request->all(), [
    ])->sometimes('DealerContact', 'url', function ($input) {
        return $input->DealerContactType === 'telegram';
    })->sometimes('DealerContact', 'regex:/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/', function ($input) {
        return $input->DealerContactType === 'phone';
    })->sometimes('DealerContact', 'email', function ($input) {
        return $input->DealerContactType === 'email';
    });

    // dd($validate, $request->all());

    $newImageName = time() . '-DealersDen-'. $user->id . '-' . $request->DealerName . '.' . $request->DealerLogo->extension();

        $request->DealerLogo->move(public_path('storage'), $newImageName);

        // dd($request, $validate, $newImageName);

        if (!Dealer::where('user_id', $user->id)->first())
        {
            Dealer::create([
                'user_id' => $user->id,
                'name' => $validate['DealerName'],
                'info' => $validate['DealerSort'],
                'gallery_link' => $validate['DealerGalerie'],
                'icon_file' => $newImageName,
                'contact_way' => $validate['DealerContactType'],
                'contact' => $validate['DealerContact'],
                'space' => $validate['DealerSpace'],
            ]);
        }

    // Weiterleitung nach erfolgreichem Speichern
    return redirect('dealersden');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dealer = Dealer::where('id', $id)->first();
        return view('DDAS.dealersden.show', compact('dealer'));
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
