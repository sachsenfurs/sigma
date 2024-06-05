<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Settings\AppSettings;
use DeepL\DeepLException;
use DeepL\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TranslateController extends Controller
{
    public function __invoke(Request $request) {
//        Gate::authorize("post");

        $authKey = app(AppSettings::class)->deepl_api_key;
        $result = "";
        try {
            $translator = new Translator($authKey);
            $result = $translator->translateText(
                $request->get("text"),
                app(AppSettings::class)->deepl_source_lang,
                app(AppSettings::class)->deepl_target_lang
            );
        } catch(DeepLException $e) {

        }
        return [ $result ];
    }
}
