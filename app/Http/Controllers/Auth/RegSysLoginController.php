<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\OAuth2\RegSysProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class RegSysLoginController extends Controller
{
    private RegSysProvider $provider;
    public function __construct() {
        $this->middleware('guest')->except('logout');

        $this->provider = new RegSysProvider([
            'clientId' => config("app.regsys_oauth.clientId"),
            'clientSecret' => config("app.regsys_oauth.clientSecret"),
            'redirectUri' => config("app.regsys_oauth.redirectUri"),
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

            dd($resourceOwner);

            /**
              ["response":protected]=>
              array(9) {
                ["id"]=>
                string(1) "1"
                ["nickname"]=>
                string(6) "Kidran"
                ["telegram_id"]=> 123456789
                string(0) ""
                ["language"]=>
                string(2) "de"
                ["paid"]=>
                bool(true)
                ["groups"]=>
                array(2) {
                  [0]=>
                  string(9) "fursuiter"
                  [1]=>
                  string(5) "staff"
                }
                ["checkedin"]=>
                bool(false)
                ["avatar"]=>
                string(59) "https://regtest.kidran.de/uploads/e621983edaad4048d0c30.jpg"
                ["avatar_thumb"]=>
                string(59) "https://regtest.kidran.de/uploads/e62135ecc114efb8a3440.jpg"
              }
             */

            // TODO: .... User erstellen usw.


        } catch (IdentityProviderException $e) {
            return redirect("/login")->withErrors(['email' => "Token ungültig"]);
        }
    }

}