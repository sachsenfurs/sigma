<?php

namespace App\Filament\Resources\SocialResource\Pages;

use App\Filament\Resources\SocialResource;
use App\Models\Info\Social;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocial extends EditRecord
{
    protected static string $resource = SocialResource::class;

    protected function getHeaderActions(): array {
        return [
            Actions\DeleteAction::make()
                ->after(fn() => Social::clearCache()),
        ];
    }

    public function afterSave() {
        Social::clearCache();
    }
}
