<?php

namespace App\Http\Middleware;

use App\Services\OAuthProfileUpdateService;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next, ...$guards) {
        $this->authenticate($request, $guards);

        $refreshToken   = auth()->user()->refresh_token;
        $updatedAt      = auth()->user()->token_updated_at;

        // TODO: put this in a separate worker queue... maybe.. (?)
        if($refreshToken AND $updatedAt AND auth()->user()->token_updated_at->addMinutes((int)config("auth.oauth.refresh_interval"))->isPast()) {
            if(auth()->user()->token_updated_at->addMinutes((int)config("auth.oauth.refresh_token_lifetime"))->isPast()) {
                Auth::logout();
                $this->unauthenticated($request, $guards);
            } else {
                try {
                    (new OAuthProfileUpdateService())->byRefreshToken($refreshToken);
                    auth()->user()->refresh();
                } catch(IdentityProviderException $e) {
                    info("couldn't update refresh_token for user id " . auth()->id(), $e, auth()->user()->refresh_token);
                    auth()->user()->token_updated_at = now(); // update token time so it won't try to update on every request from now on
                    auth()->user()->save();
                }
            }
        }

        return $next($request);
    }

    /**
     * Get the path the users should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string {
        if($request->getRequestUri() == "login")
            return null;
        if (!$request->expectsJson()) {
            Session::put('redirect', $request->getRequestUri());
            return route('login');
        }
        return null;
    }

    protected function unauthenticated($request, array $guards): void {
        Session::flash("error", __("Please log in to access this page"));
        parent::unauthenticated($request, $guards);
    }
}
