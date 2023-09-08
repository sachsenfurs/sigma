<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function connect(Request $request)
    {
        $this->authorize("login");
        
        auth()->user()->update([
            'telegram_user_id' => $request['id']
        ]);

        return redirect()->back()->withSuccess(__('Telegram Connected!'));
    }
}
