<?php

namespace App\Filament\Resources\SigHostResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SigHostResource;
use Filament\Actions;
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
