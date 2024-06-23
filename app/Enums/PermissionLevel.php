<?php

namespace App\Enums;

use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Support\Contracts\HasLabel;

enum PermissionLevel: int implements HasLabel
{
    use AttributableEnum;

    #[Name('No Permission')]
    case NO         = 0;

    #[Name('Read')]
    case READ       = 1 << 0;

    #[Name('Write')]
    case WRITE      = 1 << 1;

    #[Name('Delete')]
    case DELETE     = 1 << 2;

    #[Name('Admin')]
    case ADMIN      = 1 << 3;

    /**
     * Filament translation
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->name();
    }
}
