<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Models\Ddas\Dealer;
use Illuminate\Support\Facades\Gate;

class DealersDenController extends Controller
{
    public function index() {
//        $this->authorize("viewAny", Dealer::class);

        return view('ddas.dealers.index');
    }

    public function create() {
        if(($response = Gate::inspect("create", Dealer::class))->denied())
            return redirect()->route("home")->withError($response->message());

        return view("ddas.dealers.create");
    }
}
