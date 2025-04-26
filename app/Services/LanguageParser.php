<?php

namespace App\Services;

class LanguageParser
{
    public static function parse($string, $destLang=null): string {
        if(!$destLang)
            $destLang = app()->getLocale();

        // $string = "{de}Deutscher Text{/de}{en}English text{/en}"

        // strip destLang markup
        $string = preg_replace("/\{$destLang\}(.*?)\{\/$destLang\}/s", "$1", $string);

        // remove other languages
        $string = preg_replace("/\{([A-Za-z]{2,3})\}(.*?)\{\/\\1\}/s", "", $string);

        return $string ?? "";
    }
}
