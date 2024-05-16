<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowPickup;
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
