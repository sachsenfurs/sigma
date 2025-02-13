<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\OAuth2\RegSysProvider;
use App\OAuth2\RegSysResourceOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class RegSysLoginController extends Controller
{
    private RegSysProvider $provider;
    public function __construct() {
        $this->middleware('guest')->except('logout');

        $this->provider = new RegSysProvider([
            'clientId' => config("app.regsys_oauth.clientId"),
            'clientSecret' => config("app.regsys_oauth.clientSecret"),
            'redirectUri' => route("oauth_redirect"),
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

            /**
             * @var $resourceOwner RegSysResourceOwner
             */
            $resourceOwner = $this->provider->getResourceOwner($accessToken);

            $userData = [
                'name' => $resourceOwner->getNickname(),
                'email' => $resourceOwner->getEmail(),
                'reg_id' => $resourceOwner->getId(),
                'checkedin' => $resourceOwner->getCheckedIn(),
                'language' => $resourceOwner->getLanguage(),
                'telegram_id' => $resourceOwner->getTelegramId(),
                'groups' => $resourceOwner->getGroups(),
                'avatar' => $resourceOwner->getAvatar(),
                'avatar_thumb' => $resourceOwner->getAvatarThumb(),
            ];

            $userToLogin = User::where("reg_id", $resourceOwner->getId())->first();

            if($userToLogin)
                $userToLogin->update($userData);
            else
                $userToLogin = User::create($userData);

            auth()->login($userToLogin);

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
            $route = Session::get('redirect', route("home"));
            return redirect($route);
        } catch (IdentityProviderException $e) {
            return redirect("/login")->withErrors(['email' => "Token ungültig"]);
        }
    }

}
