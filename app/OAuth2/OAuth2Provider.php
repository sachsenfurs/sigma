<?php

namespace App\OAuth2;

use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class OAuth2Provider extends AbstractProvider
{
    public function getBaseAuthorizationUrl()
    {
        return config("app.oauth.domain") . "/oauth/authorize";
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return config("app.oauth.domain") . "/oauth/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return config("app.oauth.domain") . "/oauth/profile?access_token=" . $token->getToken();
    }

    protected function getDefaultScopes()
    {
        return [  ];
    }

    /**
     * @param array $response
     * @param AccessToken $token
     * @return OAuth2ResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token): OAuth2ResourceOwner
    {
        return new OAuth2ResourceOwner($response);
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        return $data;
    }

    /**
     * @param array $response
     * @param AbstractGrant $grant
     * @return AccessToken
     * @throws IdentityProviderException
     */
    protected function createAccessToken(array $response, AbstractGrant $grant)
    {
        if(empty($response['access_token']))
            throw new IdentityProviderException("Fehler bei der Authentifizierung", 100, $response);
        return new AccessToken($response);
    }
}
