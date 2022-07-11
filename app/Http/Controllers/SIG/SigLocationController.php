<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use Illuminate\Http\Request;

class SigLocationController extends Controller
{
    public function index() {
        $locations = SigLocation::withCount("sigEvents")->get();

        return view("locations.index", compact("locations"));
    }
}
