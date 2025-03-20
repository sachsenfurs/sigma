<?php

namespace App\Http\Controllers\Ddas;

use App\Http\Controllers\Controller;
use App\Policies\Ddas\DealerPolicy;

class DealersDenController extends Controller
{
    public function index() {
//        $this->authorize("viewAny", Dealer::class);

        return view('ddas.dealers.index');
    }

    public function create() {
        if(!DealerPolicy::isWithinDeadline())
            return redirect()->route("home")->withError(__("The deadline for dealers application has already passed"));
        return view("ddas.dealers.create");
    }
}
