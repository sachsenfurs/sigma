<?php

namespace App\Http\Controllers;


use App\Models\LostFoundItem;

class LostFoundItemController extends Controller
{
    public function index() {
        $this->authorize("viewAny", LostFoundItem::class);
        return view("lostfound.index");
    }
}
