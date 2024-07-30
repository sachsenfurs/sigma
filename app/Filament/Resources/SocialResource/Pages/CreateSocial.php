<?php

namespace App\Filament\Resources\SocialResource\Pages;

use App\Filament\Resources\SocialResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Cache;

class CreateSocial extends CreateRecord
{
    protected static string $resource = SocialResource::class;

    public function afterCreate() {
        Cache::forget("footer");
    }
}
