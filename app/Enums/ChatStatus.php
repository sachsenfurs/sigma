<?php

namespace App\Enums;

use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Support\Contracts\HasLabel;

enum ChatStatus: int implements HasLabel
{
    use AttributableEnum;

    #[Name('Closed')]
    case CLOSED     = 0;

    #[Name('Open')]
    case OPEN       = 1;
    #[Name('Locked')]
    case LOCKED     = 2;

    /**
     * Filament translation
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->name();
    }
}
