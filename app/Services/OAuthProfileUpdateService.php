<?php

namespace App\Services;

use App\Http\OAuth2\RegSysProvider;
use App\Http\OAuth2\RegSysResourceOwner;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;

class OAuthProfileUpdateService
{
    private RegSysProvider $provider;
    public function __construct() {
        $this->provider = new RegSysProvider([
            'clientId' => config("auth.oauth.client_id"),
            'clientSecret' => config("auth.oauth.client_secret"),
            'redirectUri' => route("oauth_redirect"),
        ]);
    }

    public function getProvider(): RegSysProvider {
        return $this->provider;
    }

    /**
     * @throws IdentityProviderException
     * @throws GuzzleException
     */
    public function byAuthCode(string $code): User {
        $accessToken = $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        return $this->updateUser($accessToken);
    }

    /**
     * @throws IdentityProviderException
     * @throws GuzzleException
     */
    public function byRefreshToken(string $refreshToken): User {
        $accessToken = $this->provider->getAccessToken('refresh_token', [
            'refresh_token' => $refreshToken,
        ]);

        return $this->updateUser($accessToken);
    }

    /**
     * @throws GuzzleException
     * @throws IdentityProviderException
     */
    private function updateUser(AccessToken $accessToken): User {
        /**
         * @var $resourceOwner RegSysResourceOwner
         */
        $resourceOwner = $this->provider->getResourceOwner($accessToken);

        $userData = [
            'name'              => $resourceOwner->getNickname(),
            'email'             => $resourceOwner->getEmail(),
            'reg_id'            => $resourceOwner->getId(),
            'checkedin'         => $resourceOwner->getCheckedIn(),
            'paid'              => $resourceOwner->hasPaid(),
            'language'          => $resourceOwner->getLanguage(),
            'telegram_id'       => $resourceOwner->getTelegramId(),
            'groups'            => $resourceOwner->getGroups(),
            'avatar'            => $resourceOwner->getAvatar(),
            'avatar_thumb'      => $resourceOwner->getAvatarThumb(),
            'refresh_token'     => $accessToken->getRefreshToken(),
            'token_updated_at'  => now(),
        ];

        return User::where("reg_id", $resourceOwner->getId())
            ->limit(1)
            ->updateOrCreate(
                [   // where
                    'reg_id' => $resourceOwner->getId()
                ],
                $userData
            );
    }
}
