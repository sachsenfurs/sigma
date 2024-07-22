<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Models\Ddas\Dealer;

class DealersDenController extends Controller
{
    public function index() {
//        $this->authorize("viewAny", Dealer::class);

        return view('ddas.dealers.index');
    }

    public function create() {
        return view("ddas.dealers.create");
    }
}
