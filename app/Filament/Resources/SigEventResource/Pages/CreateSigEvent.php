<?php

namespace App\Filament\Resources\SigEventResource\Pages;

use App\Filament\Resources\SigEventResource;
use App\Models\SigEvent;
use Filament\Resources\Pages\CreateRecord;

class CreateSigEvent extends CreateRecord
{
    protected static string $resource = SigEventResource::class;

    protected function authorizeAccess(): void {
        $this->authorize("deleteAny", SigEvent::class);
    }

}
