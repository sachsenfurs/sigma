<?php

namespace App\OAuth2;

use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class RegSysProvider extends \League\OAuth2\Client\Provider\AbstractProvider
{

    public function getBaseAuthorizationUrl()
    {
        return config("app.regsys_oauth.domain") . "/?page=Authorize";
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return config("app.regsys_oauth.domain") . "/?page=AuthorizeToken";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return config("app.regsys_oauth.domain") . "/?page=AuthorizeToken&profile&access_token=" . $token->getToken();
    }

    protected function getAuthorizationHeaders($token = null) {
        return [
            'Authorization' => "Bearer $token"
        ];
    }

    protected function getDefaultScopes()
    {
        return [  ];
    }

    /**
     * @param array $response
     * @param AccessToken $token
     * @return RegSysResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token): RegSysResourceOwner
    {
        return new RegSysResourceOwner($response);
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
