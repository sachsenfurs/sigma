<?php

namespace App\Http\OAuth2;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class RegSysResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    public function __construct(protected array $response = []) {}

    public function getId(): int {
        return $this->getValueByKey($this->response, "id");
    }

    public function getCheckedIn(): bool {
        return $this->getValueByKey($this->response, "checkedin", false);
    }

    public function getNickname(): string {
        return $this->getValueByKey($this->response, "nickname");
    }

    public function getEmail(): string {
        return $this->getValueByKey($this->response, "email", "");
    }

    public function getLanguage(): string {
        return $this->getValueByKey($this->response, "language", "en");
    }

    public function getTelegramId(): string {
        return $this->getValueByKey($this->response, "telegram_id");
    }

    public function getGroups(): array {
        return $this->getValueByKey($this->response, "groups", []);
    }

    public function getAvatar(): string {
        return $this->getValueByKey($this->response, "avatar");
    }

    public function getAvatarThumb(): string {
        return $this->getValueByKey($this->response, "avatar_thumb");
    }

    public function toArray(): array {
        return $this->response;
    }
}
