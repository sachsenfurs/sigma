<?php

namespace App\Enums;

use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Support\Contracts\HasLabel;

enum Permission implements HasLabel
{
    use AttributableEnum;

    #[Name('Super Admin')]
    case MANAGE_ADMIN;

    #[Name('Manage Settings')]
    case MANAGE_SETTINGS;

    #[Name('Manage Users')]
    case MANAGE_USERS;

    #[Name('Manage Events')]
    case MANAGE_EVENTS;

    #[Name('Manage Forms')]
    case MANAGE_FORMS;

    #[Name('Manage Locations')]
    case MANAGE_LOCATIONS;

    #[Name('Manage Hosts')]
    case MANAGE_HOSTS;

    #[Name('Manage Posts')]
    case MANAGE_POSTS;

    #[Name('Manage Art Show')]
    case MANAGE_ARTSHOW;

    #[Name('Manage Dealers')]
    case MANAGE_DEALERS;

    #[Name('Manage Chats')]
    case MANAGE_CHATS;


    /**
     * Filament translation
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->name();
    }
}
