<?php

namespace App\Filament\Resources\SigLocations\Pages;

use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\SigLocations\SigLocationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSigLocation extends ViewRecord
{
    protected static string $resource = SigLocationResource::class;

    public function getHeading(): string|Htmlable {
        return $this->record->name;
    }

}
