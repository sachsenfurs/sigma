<?php

namespace App\Filament\Actions;

use App\Services\Translator;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ServiceAction
{

    public static function translateComponent($fromComponent, $toComponent): Action {
        return Action::make('translate')
            ->label("Translate")
            ->translateLabel()
            ->icon('heroicon-o-language')
            ->action(fn(Get $get, Set $set) => $set($toComponent, app(Translator::class)->translate($get($fromComponent))));
    }
}
