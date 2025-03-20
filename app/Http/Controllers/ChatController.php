<?php

namespace App\Http\Controllers;

use App\Settings\ChatSettings;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function __construct() {
        if(!app(ChatSettings::class)->enabled)
            abort(403);
    }

    public function index(Request $request) {
//        $this->authorize("viewAny", Chat::class);
        return view("chats.show");
    }

}
