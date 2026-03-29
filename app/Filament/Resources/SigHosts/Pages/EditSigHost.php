<?php

namespace App\Filament\Resources\SigHosts\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SigHosts\SigHostResource;
use Filament\Resources\Pages\EditRecord;

class EditSigHost extends EditRecord
{
    protected static string $resource = SigHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->modalHeading(__('Delete Host')),
        ];
    }

    public function getHeading(): string
    {
        return __('Edit Host');
    }
}
