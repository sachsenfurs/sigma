<?php

namespace App\Http\Controllers;

class AnnouncementsController extends Controller
{

    public function index() {
        return view("announcements.index");
    }

}
