<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Models\Ddas\ArtshowItem;
use Illuminate\Http\Request;

class ArtshowCardsController extends Controller
{

    public function __invoke(Request $request) {
        $this->authorize("create", ArtshowItem::class);
        $items = ArtshowItem::auctionableItems()->withCount("artshowBids")->get();
        return view("ddas.artshow.cards", compact("items"));
    }
}
