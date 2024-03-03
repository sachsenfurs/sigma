<?php

namespace App\Filament\Resources\SigTagResource\Pages;

use App\Filament\Resources\SigTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSigTag extends CreateRecord
{
    protected static string $resource = SigTagResource::class;

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return __('Create Tag');
    }
}
