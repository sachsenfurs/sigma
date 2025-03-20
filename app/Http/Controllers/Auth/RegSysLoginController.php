<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OAuthProfileUpdateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class RegSysLoginController extends Controller
{

    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    public function index(Request $request) {
        $provider = (new OAuthProfileUpdateService())->getProvider();

        // initialize state
        $authorizationUrl = $provider->getAuthorizationUrl();

        // store state to compare later
        $request->session()->put("state", $provider->getState());

        // Redirect the users to the authorization URL.
        return redirect($authorizationUrl);
    }

    public function redirect(Request $request) {
        $validated = $request->validate([
            'code' => "required",
            'state' => "required|in:".$request->session()->get("state"),
        ]);

        try {
            $userToLogin = (new OAuthProfileUpdateService())->byAuthCode($validated['code']);

            auth()->login($userToLogin);

            return redirect(
                Session::get('redirect', route("home"))
            );
        } catch (IdentityProviderException $e) {
            return redirect("/login")->withError($e->getMessage());
        }
    }

}
