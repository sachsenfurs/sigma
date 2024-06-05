<?php

namespace App\Http\Controllers;

use App\Policies\LostFoundItemPolicy;
use Illuminate\Http\Request;

class LostFoundItemController extends Controller
{
    public function index() {
        $this->authorize("viewAny", LostFoundItem::class);
        return view("lostfound.index");
    }
}
