<?php

namespace App\Http\Controllers\DDAS;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DDAS\ArtshowArtist;
use App\Models\DDAS\ArtshowItem;
use App\Models\DDAS\ArtshowBid;
use App\Models\DDAS\ArtshowPickup;
use Illuminate\Http\Request;

class ArtshowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $as_items = ArtshowItem::all();
        $as_artists = ArtshowArtist::all();
        // dd($as_items, $as_artists);
        return view('DDAS.artshow.index', compact('as_items', 'as_artists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('id', auth()->user()->id)->first();

        $artist = ArtshowArtist::where('user_id', $user->id)->first();

        // dd($user, $artist);

        return view("ddas.artshow.create", compact([
            'user', 'artist']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

        $validate = $request->validate([
            'ArtistName' => 'sometimes|required|string|max:255',
            'ArtistWeb' => 'sometimes|required|url',
            'ArtistItemName' => 'required|string|max:255',
            'description' => 'required|string|min:100',
            'ArtistItemAdditionalInfo' => 'nullable',
            'starting_bid' => 'required|numeric|min:0|max:10000',
            'charity_percentage' => 'required|integer|min:35|max:100',
            'ArtistItemImage' => 'required|mimes:jpeg,png,jpg|max:5048',
        ]);

        $newImageName = time() . '-ArtShow-'. $user->id . '-' . $request->ArtistItemName . '.' . $request->ArtistItemImage->extension();

        $request->ArtistItemImage->move(public_path('storage'), $newImageName);

        // dd($request->all());

        if (!ArtshowArtist::where('user_id', $user->id)->first())
        {
            ArtshowArtist::create([
                'user_id' => $user->id,
                'name' => $validate['ArtistName'],
                'social' => $validate['ArtistWeb'],
            ]);
        }

        $artist = ArtshowArtist::where('user_id', $user->id)->first();

        // dd($artist);

        ArtshowItem::create([
            'artshow_artist_id' => $artist->id,
            'name' => $validate['ArtistItemName'],
            'description' => $validate['ArtistItemDescriptionDE'],
            'starting_bid' => $validate['ArtistItemStartBid'],
            'charity_percentage' => $validate['ArtistItemCharity'],
            'additional_info' => $validate['ArtistItemAdditionalInfo'],
            'image_file' => $newImageName,
        ]);

        return redirect('ddas.artshow');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $item = ArtshowItem::where('id',$id)->first();
        $artists = ArtshowArtist::all();

        $as_artist = null;

        foreach ($artists as $artist)
        {
            if ($artist->id == $item->artshow_artist_id)
            {
                $as_artist = $artist;
            }
        }
        if(request()->wantsJson())
            return compact('as_artist', 'item', 'user');

        return view('ddas.artshow.show', compact('as_artist', 'item', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $artist = ArtshowArtist::find($id);
        $item = ArtshowItem::where('artshow_artist_id', $id)->find($id);

        // dd($artist, $items);
        return view('ddas.artshow.edit', compact('artist', 'item'));
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
