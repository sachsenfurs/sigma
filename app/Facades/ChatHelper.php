<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ChatHelper extends Facade
{
    protected static function getFacadeAccessor() {
        return \App\Helper\Chat\ChatHelper::class;
    }
}
