<?php

namespace App\Enums;

use App\Enums\Attributes\Color;
use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ChatStatus: int implements HasLabel, HasColor
{
    use AttributableEnum;

    #[Name('Closed')]
    #[Color(\Filament\Support\Colors\Color::Green)]
    case CLOSED     = 0;

    #[Name('Open')]
    #[Color(\Filament\Support\Colors\Color::Red)]
    case OPEN       = 1;
    #[Name('Locked')]
    #[Color(\Filament\Support\Colors\Color::Gray)]
    case LOCKED     = 2;

    /**
     * Filament translation
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->name();
    }

    public function getColor(): string|array|null {
        return $this->color();
    }
}
