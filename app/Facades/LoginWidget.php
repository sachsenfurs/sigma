<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LoginWidget extends Facade
{
    protected static function getFacadeAccessor() {
        return \App\Helper\Telegram\LoginWidget::class;
    }
}
