<?php

namespace App\Filament\Resources\SigLocationResource\Pages;

use App\Filament\Resources\SigLocationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSigLocation extends ViewRecord
{
    protected static string $resource = SigLocationResource::class;

    public function getHeading(): string|\Illuminate\Contracts\Support\Htmlable {
        return $this->record->name;
    }

}
