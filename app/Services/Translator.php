<?php

namespace App\Services;

use App\Settings\AppSettings;
use DeepL\DeepLException;
use Illuminate\Support\Facades\Cache;

class Translator
{
    private ?\DeepL\Translator $translator = null;

    public function __construct(private readonly string $authKey,
                                private readonly string $sourceLang = "de-DE",
                                private readonly string $targetLang = "en-US") {
        try {
            $this->translator = new \DeepL\Translator($this->authKey);
        } catch(DeepLException $e) {

        }
    }

    public function translate($text, $sourceLang = null, $targetLang = null) {
        if($sourceLang == null)
            $sourceLang = $this->sourceLang;
        if($targetLang == null)
            $targetLang = $this->targetLang;


        try {
            $result =  $this->translator?->translateText(
                $text,
                $sourceLang,
                $targetLang
            );

            return Cache::remember(md5($result->text . $sourceLang . $targetLang), 3600 * 24, fn() => $result->text) ?? $text;
        } catch(\Exception $e) {
            return $text;
        }
    }
}
