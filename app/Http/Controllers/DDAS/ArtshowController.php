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

        return view("DDAS.artshow.create", compact([
            'user', 'artist']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();

        if (!ArtshowArtist::where('user_id', $user->id)->first())
        {
            ArtshowArtist::created([
                'user_id' => $user->id,
                'name' => $request->name,
                'social' => $request->social,
            ]);
        }
        else
        {
            ArtshowItem::create([
                'artshow_artist_id' => $request->artshow_artist_id,
                'name' => $request->name,
                'description' => $request->description,
                'description_en' => $request->description_en,
                'starting_bid' => $request->starting_bid,
                'charity_percentage' => $request->charity_percentage,
                'additional_info' => $request->additional_info,
                'image_file' => $request->image_file,
            ]);
        }
        return redirect('artshow');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $artist = ArtshowArtist::find($id);
        $item = ArtshowItem::where('artshow_artist_id', $id)->find($id);

        // dd($artist, $items);
        return view('DDAS.artshow.show', compact('artist', 'item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $artist = ArtshowArtist::find($id);
        $item = ArtshowItem::where('artshow_artist_id', $id)->find($id);

        // dd($artist, $items);
        return view('DDAS.artshow.edit', compact('artist', 'item'));
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
