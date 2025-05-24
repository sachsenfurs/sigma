<?php

namespace App\Filament\Resources\Ddas\ArtshowItemResource\Pages;

use App\Filament\Resources\Ddas\ArtshowArtistResource;
use App\Filament\Resources\Ddas\ArtshowItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class EditArtshowItem extends EditRecord
{
    protected static string $resource = ArtshowItemResource::class;

    protected function getRedirectUrl(): ?string {
        return $this->previousUrl;
    }

    public function getSubheading(): string|Htmlable|null {
        $user       = ($this->record->artist->user?->reg_id ?? "") . " - " . $this->record->artist->user?->name ?? "";
        $artist     = $this->record->artist->name;
        $artistUrl  = ArtshowArtistResource::getUrl('edit', ['record' => $this->record->artist]);
        return new HtmlString(__("User") . ": $user<br>" . __("Artist") . ": <a href='$artistUrl'>$artist</a>");
    }

    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
