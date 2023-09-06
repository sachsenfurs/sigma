<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function connect(Request $request)
    {
        $this->authorize("login");
        dd($request);
        auth()->user()->telegram_user_id = $request->input('id');

        return redirect()->back()->withSuccess(__('Telegram Connected!'));
    }
}
