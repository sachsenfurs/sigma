<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use DeepL\DeepLException;
use DeepL\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TranslateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Gate::authorize("post");


        $authKey = config("app.deeplApiKey");
        $result = "";
        try {
            $translator = new Translator($authKey);
            $result = $translator->translateText($request->get("text"), null, "en-US");
        } catch(DeepLException $e) {

        }

        return [ $result ];

    }
}
