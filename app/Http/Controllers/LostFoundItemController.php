<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LostFoundItemController extends Controller
{
    public function index() {
        return view("lostfound.index");
    }
}
