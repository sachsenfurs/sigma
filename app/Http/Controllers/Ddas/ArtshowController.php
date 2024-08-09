<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Models\Ddas\ArtshowItem;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ArtshowController extends Controller
{
    public function index() {
        $error = false;
        try {
            $this->authorize("viewAny", ArtshowItem::class);
        } catch(AuthorizationException $e) {
            $error = $e->getMessage();
        }
        return view('ddas.artshow.index', compact('error'));
    }

    public function create() {
        return view("ddas.artshow.create");
    }

    public function show(ArtshowItem $artshowItem) {
        $this->authorize('view', $artshowItem);

//        return $artshowItem;
    }

}
