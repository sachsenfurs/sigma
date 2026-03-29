<?php

namespace App\Filament\Resources\Socials\Pages;

use App\Filament\Resources\Socials\SocialResource;
use App\Models\Info\Social;
use Filament\Resources\Pages\CreateRecord;

class CreateSocial extends CreateRecord
{
    protected static string $resource = SocialResource::class;

    public function afterCreate() {
        Social::clearCache();
    }
}
