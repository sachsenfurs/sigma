<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;

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

        return view('home', compact([
            'registrations',
            'favorites',
            'posts'
        ]));
    }
}
