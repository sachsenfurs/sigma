<?php

namespace App\Http\Controllers\Ddas1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DDAS\ArtshowArtist;
use App\Models\DDAS\ArtshowItem;
use App\Models\DDAS\ArtshowBid;
use App\Models\DDAS\ArtshowPickup;
use Illuminate\Http\Request;

class ArtshowController extends Controller
{
    public function index() {
        return view('ddas.artshow.index');
    }

    public function create() {
        return view("ddas.artshow.create");
    }

    public function show(ArtshowItem $artshowItem) {
        $this->authorize('view', $artshowItem);
        return $artshowItem;
    }

}
