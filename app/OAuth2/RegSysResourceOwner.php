<?php

namespace App\OAuth2;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class RegSysResourceOwner implements ResourceOwnerInterface
{

    use ArrayAccessorTrait;

    protected array $response;

    public function __construct(array $response = []) {
        $this->response = $response;
    }
    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, "id");
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
