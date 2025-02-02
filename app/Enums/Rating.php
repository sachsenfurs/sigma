<?php

namespace App\Enums;

use App\Enums\Attributes\Color;
use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Rating: int implements HasLabel, HasColor
{
    use AttributableEnum;

    #[Name('SFW')]
    #[Color(\Filament\Support\Colors\Color::Stone)]
    case SFW        = 0;

    #[Name('NSFW')]
    #[Color(\Filament\Support\Colors\Color::Red)]
    case NSFW       = 1;


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
