<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class Authenticate extends Middleware
{
    /**
     * Get the path the users should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if($request->getRequestUri() == "login")
            return null;
        if (! $request->expectsJson()) {
            Session::put('redirect', $request->getRequestUri());
            return route('login');
        }
    }

    protected function unauthenticated($request, array $guards) {
        Session::flash("error", __("Please log in to access this page"));
        parent::unauthenticated($request, $guards);
    }
}
