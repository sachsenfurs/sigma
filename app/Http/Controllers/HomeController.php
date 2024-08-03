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
        $favorites      = auth()->user()->favorites()->upcoming()->with("timetableEntry")->get()->sortBy(function($query) {
            return $query->timetableEntry->start;
        })->all();

        $posts      = Post::latest()->limit(6)->get();
        
        $preConMode = false;
        $preConStartDate = strtotime(app(AppSettings::class)->event_start->subDays(3));
        $currentDate = strtotime(Carbon::now()->toDateString());
        if ($preConStartDate > $currentDate) {
            $preConMode = true;
        }

        //dd($registrations->first()->sigEvent()->attendees());

        return view('home', compact([
            'registrations',
            'favorites',
            'posts',
            'preConMode'
        ]));
    }
}
