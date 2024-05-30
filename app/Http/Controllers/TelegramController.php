<?php

namespace App\Http\Controllers;

use App\Facades\LoginWidget;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function connect(Request $request)
    {
        $this->authorize("login");
        if($telegramUser = LoginWidget::validate($request)) {
            auth()->user()->update([
                'telegram_user_id' => $request['id']
            ]);

            return redirect()->back()->withSuccess(__('Telegram Connected!'));
        }

        return redirect()->back()->withErrors(__('An error occured while connecting Telegram!'));
    }
}
