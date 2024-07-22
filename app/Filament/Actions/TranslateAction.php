<?php

namespace App\Filament\Actions;

use App\Models\SigEvent;
use App\Services\Translator;
use App\Settings\AppSettings;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;

class TranslateAction extends ServiceAction
{
    /**
     * Translates to secondary language (config default: en-US)
     * @param string $fromComponent
     * @param string $toComponent
     * @return Action
     */
    public static function translateToSecondary(string $fromComponent, string $toComponent): Action {
        return self::translate(
            $fromComponent,
            $toComponent,
            app(AppSettings::class)->deepl_source_lang,
            app(AppSettings::class)->deepl_target_lang
        );
    }

    /**
     * Translates to primary language (config default: de)
     * @param string $fromComponent
     * @param string $toComponent
     * @return Action
     */
    public static function translateToPrimary(string $fromComponent, string $toComponent): Action {
        return self::translate(
            $fromComponent,
            $toComponent,
            app(AppSettings::class)->deepl_target_lang,
            app(AppSettings::class)->deepl_source_lang
        );
    }

    private static function translate(string $fromComponent, string $toComponent, ?string $fromLang=null, ?string $toLang=null) {
        return Action::make('translate')
             ->label(__("Translate from :LANGUAGE", ['language' => $fromLang]))
             ->icon('heroicon-o-language')
             ->action(function(Get $get, Set $set) use ($toLang, $fromLang, $fromComponent, $toComponent) {
                 if(!app(AppSettings::class)->deepl_api_key)
                     return self::notAvailable();

                 $set(
                     $toComponent,
                     app(Translator::class)->translate(
                         $get($fromComponent),
                         $fromLang,
                         $toLang
                     )
                 );
             });
    }
}
