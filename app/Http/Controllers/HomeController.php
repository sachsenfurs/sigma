<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;
use App\Settings\AppSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

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
