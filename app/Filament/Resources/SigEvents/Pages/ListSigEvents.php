<?php

namespace App\Filament\Resources\SigEvents\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SigEvents\SigEventResource;
use App\Models\SigEvent;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;

class ListSigEvents extends ListRecords
{
    protected static string $resource = SigEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->authorize("deleteAny", SigEvent::class),
        ];
    }

    public function getTitle(): string|Htmlable {
        return __("SIG Overview");
    }
}
