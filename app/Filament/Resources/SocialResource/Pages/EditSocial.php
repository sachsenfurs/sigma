<?php

namespace App\Filament\Resources\SocialResource\Pages;

use App\Filament\Resources\SocialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditSocial extends EditRecord
{
    protected static string $resource = SocialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(fn() => Cache::forget("footer")),
        ];
    }

    public function afterSave() {
        Cache::forget("footer");
    }
}
