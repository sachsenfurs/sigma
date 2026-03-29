<?php

namespace App\Filament\Traits;

use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

trait HasActiveIcon
{

    public static function getActiveNavigationIcon(): string|\BackedEnum|Htmlable|null {

        if(self::getNavigationIcon() instanceof Heroicon)
            $name = "heroicon-" . self::getNavigationIcon()->value;
        else
            $name = self::getNavigationIcon();

        return str($name)->replace("heroicon-o", "heroicon-s");
    }
}
