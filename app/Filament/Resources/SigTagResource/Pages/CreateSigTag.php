<?php

namespace App\Filament\Resources\SigTagResource\Pages;

use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\SigTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSigTag extends CreateRecord
{
    protected static string $resource = SigTagResource::class;

    public function getHeading(): Htmlable|string
    {
        return __('Create Tag');
    }
}
