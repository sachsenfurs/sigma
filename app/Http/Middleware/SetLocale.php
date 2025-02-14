<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Number;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response {
        // Localization
        $lang = session("locale") ?? auth()->user()?->language ?? config("app.locale");
        App::setLocale($lang);
        Carbon::setLocale($lang);
        Number::useLocale($lang);

        return $next($request);
    }
}
