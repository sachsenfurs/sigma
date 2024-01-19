<?php

namespace App\Filament\Resources\SigLocationResource\Pages;

use App\Filament\Resources\SigLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSigLocation extends EditRecord
{
    protected static string $resource = SigLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
