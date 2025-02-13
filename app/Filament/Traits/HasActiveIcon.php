<?php

namespace App\Filament\Traits;

trait HasActiveIcon
{

    public static function getActiveNavigationIcon(): ?string {
        return str(self::getNavigationIcon())->replace("heroicon-o", "heroicon-s");
    }
}
