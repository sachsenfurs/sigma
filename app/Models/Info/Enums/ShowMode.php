<?php

namespace App\Models\Info\Enums;


use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Support\Contracts\HasLabel;

enum ShowMode: int implements HasLabel
{
    use AttributableEnum;

    #[Name('Show on Signage')]
    case SIGNAGE = 0;
    #[Name('Show on page footer (Text)')]
    case FOOTER_ICON = 1;

    #[Name('Show on page footer (Icon only)')]
    case FOOTER_TEXT = 2;

    public function getLabel(): ?string {
        return $this->name();
    }
}
