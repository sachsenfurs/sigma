<?php

namespace App\Http\OAuth2;

use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class RegSysProvider extends AbstractProvider
{

    public function getBaseAuthorizationUrl(): string {
        return config("auth.oauth.url");
    }

    public function getBaseAccessTokenUrl(array $params): string {
        return config("auth.oauth.token_url");
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string {
        return config("auth.oauth.resource_url") . "&access_token=" . $token->getToken();
    }

    protected function getAuthorizationHeaders($token = null): array {
        return [
            'Authorization' => "Bearer $token"
        ];
    }

    protected function getDefaultScopes(): array {
        return [];
    }

    /**
     * @param array $response
     * @param AccessToken $token
     * @return RegSysResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token): RegSysResourceOwner {
        return new RegSysResourceOwner($response);
    }

    protected function checkResponse(ResponseInterface $response, $data) {
        return $data;
    }

    /**
     * @param array $response
     * @param AbstractGrant $grant
     * @return AccessToken
     * @throws IdentityProviderException
     */
    protected function createAccessToken(array $response, AbstractGrant $grant): AccessToken {
        if(empty($response['access_token']))
            throw new IdentityProviderException("Fehler bei der Authentifizierung", 100, $response);
        return new AccessToken($response);
    }

}
