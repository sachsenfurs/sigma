<?php

namespace App\Filament\Resources\SigEventResource\Pages;

use App\Filament\Resources\SigEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditSigEvent extends EditRecord
{
    protected static string $resource = SigEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->modalHeading(__('Delete SIG')),
        ];
    }

    public function getHeading(): string|Htmlable {
        return $this->record->name_localized;
    }

    protected function getRedirectUrl(): ?string {
        return $this->previousUrl ?? self::getUrl("index");
    }

    /**
     * @return string|Htmlable
     */
    public function getTitle(): string|Htmlable {
        return __("SIG") . " - " . $this->record->name_localized . " - " . __("Edit");
    }
}
