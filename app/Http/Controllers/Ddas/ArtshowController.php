<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Models\Ddas\ArtshowItem;
use Illuminate\Support\Facades\Gate;

class ArtshowController extends Controller
{
    public function index() {
        return view('ddas.artshow.index')
            ->withError( // error => separate blade variable in view
                Gate::inspect("viewAny", ArtshowItem::class)->message()
            );
    }

    public function create() {
        return view("ddas.artshow.create")
            ->withErrors(
                Gate::inspect("create", ArtshowItem::class)->message()
            );
    }

    public function show(ArtshowItem $artshowItem) {
        $this->authorize('view', $artshowItem);

//        return $artshowItem;
    }

}
