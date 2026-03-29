<?php

namespace App\Filament\Resources\SigTagResource\Pages;

use Illuminate\Contracts\Support\Htmlable;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\SigTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSigTag extends EditRecord
{
    protected static string $resource = SigTagResource::class;

    public function getHeading(): Htmlable|string
    {
        return __('Edit Tag');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
