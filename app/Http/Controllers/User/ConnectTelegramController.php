<?php

namespace App\Http\Controllers\User;

use App\Facades\LoginWidget;
use App\Facades\NotificationService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConnectTelegramController extends Controller
{
    public function __invoke(Request $request) {
        if($telegramUser = LoginWidget::validate($request)) {
            $request->validate([
                'id' => Rule::unique(User::class, "telegram_user_id"),
            ],
            [
                'id.unique' => __("This Telegram account is already linked to someone else!"),
            ]);

            auth()->user()->update([
                'telegram_user_id' => $request['id']
            ]);

            NotificationService::enableTelegramNotifications(auth()->user());

            return redirect()->back()->withSuccess(__('Telegram Connected!'));
        }

        return redirect()->back()->withErrors(__('An error occured while connecting Telegram!'));
    }
}
