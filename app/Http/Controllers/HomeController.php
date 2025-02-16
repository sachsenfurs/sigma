<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;
use App\Settings\AppSettings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $registrations  = auth()->user()->attendeeEvents;
        $favorites      = auth()->user()->favorites()->upcoming()->with("timetableEntry")->with("timetableEntry.sigEvent")->with("timetableEntry.reminders")->orderBy("start")->get();
        $posts          = Post::latest()->limit(6)->get();
        $preConMode     = app(AppSettings::class)->isPreConMode();

        return view('home', compact([
            'registrations',
            'favorites',
            'posts',
            'preConMode'
        ]));
    }
}
