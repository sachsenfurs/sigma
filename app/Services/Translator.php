<?php

namespace App\Services;

use DeepL\DeepLException;
use Illuminate\Support\Facades\Cache;

class Translator
{
    private ?\DeepL\Translator $translator = null;

    public function __construct(
        private readonly ?string $authKey,
        private string $sourceLang = "de",
        private string $targetLang = "en-US"
    ) {
        $this->sourceLang = $this->parseLanguage($this->sourceLang);

        try {
            $this->translator = new \DeepL\Translator($this->authKey ?? "");
        } catch(DeepLException $e) {

        }
    }

    public function getUsage() {
        try {
            return $this->translator?->getUsage();
        } catch(DeepLException $e) {

        }
    }

    public function translate($text, $sourceLang = null, $targetLang = null) {
        // source language MUST BE be a 2 character language code!
        // https://developers.deepl.com/docs/v/de/resources/supported-languages#source-languages
        $sourceLang = $this->parseLanguage($sourceLang ?? $this->sourceLang);

        if($targetLang == null)
            $targetLang = $this->targetLang;

        try {
            return Cache::remember(md5($text . $sourceLang . $targetLang), 3600 * 24 * 14, function () use ($targetLang, $sourceLang, $text) {
                $result = $this->translator?->translateText(
                    $text,
                    $sourceLang,
                    $targetLang
                );

                return $result->text;
            }) ?? $text;
        } catch(\Exception $e) {
            return $text;
        }
    }

    private function parseLanguage(string $languageCode): string|null {
        if($languageCode == null)
            return null;

        $lang = explode("-", $languageCode);
        return $lang[0];
    }
}
