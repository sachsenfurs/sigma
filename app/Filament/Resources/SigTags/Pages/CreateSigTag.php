<?php

namespace App\Filament\Resources\SigTags\Pages;

use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\SigTags\SigTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSigTag extends CreateRecord
{
    protected static string $resource = SigTagResource::class;

    public function getHeading(): Htmlable|string
    {
        return __('Create Tag');
    }
}
