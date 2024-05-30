<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        if(auth()->user()?->can("manage_chats"))
            $chats = Chat::all();
        else
            $chats = auth()->user()->chats;

        return view("chats.index", compact("chats"));
    }

    public function create()
    {
        
    }

    public function store()
    {

    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
