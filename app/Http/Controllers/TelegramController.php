<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function connect(Request $request)
    {
        $this->authorize("login");
        auth()->user()->telegram_user_id = $request['id'];
    }
}
