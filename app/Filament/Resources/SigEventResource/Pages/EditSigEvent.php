<?php

namespace App\Filament\Resources\SigEventResource\Pages;

use App\Filament\Resources\SigEventResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;

class EditSigEvent extends EditRecord
{
    protected static string $resource = SigEventResource::class;


    protected function getFormActions(): array {
        return [
            Actions\Action::make("submit")
                ->label(__("Save and return"))
                ->action(function (EditSigEvent $livewire) {
                    $livewire->save();
                    $livewire->redirect($this->previousUrl ?? SigEventResource::getUrl("index"));
                }),
            Actions\Action::make("save")
                ->label(__("Save"))
                ->submit("form")
                ->keyBindings("ctrl+s")
                ->successRedirectUrl(null)
                ->color(Color::Green),
            Action::make("cancel")
                ->label(__("Cancel"))
                ->color("gray")
                ->url($this->previousUrl ?? SigEventResource::getUrl("index"))
        ];
    }

    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make()
                ->modalHeading(__('Delete SIG')),
        ];
    }

    public function getHeading(): string|Htmlable {
        return $this->record->name_localized;
    }


    /**
     * @return string|Htmlable
     */
    public function getTitle(): string|Htmlable {
        return __("SIG") . " - " . $this->record->name_localized . " - " . __("Edit");
    }
}
