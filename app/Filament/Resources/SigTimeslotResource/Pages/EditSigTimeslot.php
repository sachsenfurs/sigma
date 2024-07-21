<?php

namespace App\Filament\Resources\SigTimeslotResource\Pages;

use App\Filament\Resources\SigEventResource;
use App\Filament\Resources\SigTimeslotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;

class EditSigTimeslot extends EditRecord
{
    protected static string $resource = SigTimeslotResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\Action::make("sigEvent")
                ->label("Show Event")
                ->translateLabel()
                ->url(SigEventResource::getUrl("edit", ['record' => $this->record->timetableEntry->sigEvent]))
                ->color(Color::Gray),
            Actions\ViewAction::make(),
        ];
    }


    public function getHeading(): string|Htmlable {
        return SigTimeslotResource::getHeading($this->record);
    }

    public function getSubheading(): string|Htmlable|null {
        return SigTimeslotResource::getSubHeading($this->record);
    }

    public function getTitle(): string|Htmlable {
        return __("Time Slot") . " - " . $this->getHeading();
    }

}
