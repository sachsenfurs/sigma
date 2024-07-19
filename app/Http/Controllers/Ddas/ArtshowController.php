<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Models\Ddas\ArtshowItem;

class ArtshowController extends Controller
{
    public function index() {
//        $this->authorize("viewAny", ArtshowItem::class);
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
