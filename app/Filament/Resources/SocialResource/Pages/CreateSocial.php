<?php

namespace App\Filament\Resources\SocialResource\Pages;

use App\Filament\Resources\SocialResource;
use App\Models\Info\Social;
use Filament\Resources\Pages\CreateRecord;

class CreateSocial extends CreateRecord
{
    protected static string $resource = SocialResource::class;

    public function afterCreate() {
        Social::clearCache();
    }
}
