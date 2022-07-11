<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;

class OAuthLoginController extends Controller
{
    public \App\OAuth2\OAuth2Provider $provider;
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->provider = new \App\OAuth2\OAuth2Provider([
            'clientId' => config("app.oauth.clientId"),
            'clientSecret' => config("app.oauth.clientSecret"),
            'redirectUri' => config("app.oauth.redirectUri"),
        ]);
    }

    public function index(Request $request) {
        // initialize state
        $authorizationUrl = $this->provider->getAuthorizationUrl();

        // Get the state generated for you and store it to the session.
        $request->session()->put("state", $this->provider->getState());

        // Redirect the users to the authorization URL.
        return redirect($authorizationUrl);
    }

    public function redirect(Request $request) {
        $request->validate([
            'code' => "required",
            'state' => "required|in:".$request->session()->get("state"),
        ]);

        try {
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->get("code"),
            ]);

            $resourceOwner = $this->provider->getResourceOwner($accessToken);

            $u = User::where("email", $resourceOwner->getEmail())->first();
            if(!$u)
                return redirect("/login")->withErrors(['email' => "Account nicht zugeordnet"]);

            auth()->guard()->login($u);
            return redirect("/");
        } catch (IdentityProviderException $e) {
            return redirect("/login")->withErrors(['email' => "Token ungültig"]);
        }
    }
}
