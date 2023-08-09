<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleController extends Controller
{
    public function set(string $locale) {
        if(array_key_exists($locale, config("app.locales"))) {
            App::setLocale($locale);
            session([ "locale" => $locale ]);
            if(auth()->check()) {
                $user = auth()->user();
                $user->language = $locale;
                $user->save();
            }
        }
        return back();
    }
}
