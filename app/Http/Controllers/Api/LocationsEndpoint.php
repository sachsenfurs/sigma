<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SigLocation;
use Illuminate\Http\Request;

class LocationsEndpoint extends Controller
{
    public function index() {
        return SigLocation::all();
    }
}
