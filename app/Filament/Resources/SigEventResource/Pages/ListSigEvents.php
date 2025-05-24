<?php

namespace App\Filament\Resources\SigEventResource\Pages;

use App\Filament\Resources\SigEventResource;
use App\Models\SigEvent;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListSigEvents extends ListRecords
{
    protected static string $resource = SigEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->authorize("deleteAny", SigEvent::class),
        ];
    }

    public function getTitle(): string|Htmlable {
        return __("SIG Overview");
    }
}
