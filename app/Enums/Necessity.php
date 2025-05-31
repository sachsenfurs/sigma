<?php

namespace App\Enums;

use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Support\Contracts\HasLabel;

enum Necessity: int implements HasLabel
{
    use AttributableEnum;

    #[Name('Optional')]
    case OPTIONAL = 0;

    #[Name('Nice-to-have')]
    case NICE_TO_HAVE = 1;

    #[Name('Mandatory')]
    case MANDATORY = 2;

    public function getLabel(): ?string {
        return $this->name();
    }
}
