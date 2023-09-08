<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use pschocke\TelegramLoginWidget\Facades\TelegramLoginWidget;

class TelegramController extends Controller
{
    public function connect(Request $request)
    {
        $this->authorize("login");
        
        if($telegramUser = TelegramLoginWidget::validate($request)) {
            auth()->user()->update([
                'telegram_user_id' => $request['id']
            ]);

            return redirect()->back()->withSuccess(__('Telegram Connected!'));
        }

        return redirect()->back()->withErrors(__('An error occured while connecting Telegram!'));
    }
}
