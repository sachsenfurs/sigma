<?php

namespace App\Filament\Infolists\Components;

use Filament\Infolists\Components\Entry;

class Alert extends Entry
{
    protected string $view = 'infolists.components.alert';
    public string $subText = "";

    public function subText($text): static {
        $this->subText = $text;
        return $this;
    }
}
