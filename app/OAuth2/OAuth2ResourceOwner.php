<?php

namespace App\OAuth2;

use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class OAuth2ResourceOwner implements \League\OAuth2\Client\Provider\ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    protected array $response;

    public function __construct(array $reponse = []) {
        $this->response = $reponse;
    }
    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, "id"); // E-Mail address
    }

    public function getEmail()  {
        return $this->getValueByKey($this->response, "email");
    }
    public function getFullName()
    {
        return $this->getValueByKey($this->response, "full_name");
    }

    public function getDisplayName() : string {
        return $this->getValueByKey($this->response, "displayName");
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
