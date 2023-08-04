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


    public function getId() {
        return $this->getValueByKey($this->response, "id");
    }

    public function getNickname() {
        return $this->getValueByKey($this->response, "nickname");
    }

    public function getLanguage() {
        return $this->getValueByKey($this->response, "language", "en");
    }

    public function getTelegramId() {
        return $this->getValueByKey($this->response, "telegram_id");
    }

    public function getGroups() {
        return $this->getValueByKey($this->response, "groups", []);
    }
    public function getAvatar() {
        return $this->getValueByKey($this->response, "avatar");
    }
    public function getAvatarThumb() {
        return $this->getValueByKey($this->response, "avatar_thumb");
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
