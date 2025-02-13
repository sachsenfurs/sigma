<?php

namespace App\Http\Controllers;

class AnnouncementController extends Controller
{

    public function index() {
        return view("announcements.index");
    }

}
