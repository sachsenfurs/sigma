<?php

namespace App\Http\Controllers\DDAS;

use App\Http\Controllers\Controller;
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
        
        $artshow = ArtshowArtist::all();
        return view('DDAS.artshow.index', compact('artshow'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id = ArtshowArtist::pluck("id")->all();
        $name = ArtshowArtist::orderBy("id")->get();
        $artshow = ArtshowArtist::all();
        return view("DDAS.artshow.create", compact([
            'artshow',
            'id',
            'name',]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ArtshowArtist $asa)
    {
        return view('DDAS.artshow.show',[ 'artshow' => ArtshowArtist::all()]);
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
