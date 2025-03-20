<?php

namespace App\Http\Controllers;

use App\Settings\AppSettings;

class HomeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $preConMode     = app(AppSettings::class)->isPreConMode();

        return view('home', compact([
            'preConMode'
        ]));
    }
}
