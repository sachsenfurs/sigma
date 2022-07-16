<?php

namespace App\Http\Controllers\Sig;

use App\Http\Controllers\Controller;
use App\Models\SigHost;
use Illuminate\Http\Request;

class SigHostController extends Controller
{
    public function index() {
        $hosts = SigHost::withCount("sigEvents")->get();

        return view("hosts.index", compact("hosts"));
    }

    public function show(SigHost $host) {
        return view("hosts.show", [
            'host' => $host,
            'sigs' => $host->sigEvents,
        ]);
    }
}
