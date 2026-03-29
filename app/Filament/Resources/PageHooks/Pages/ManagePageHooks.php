<?php

namespace App\Filament\Resources\PageHooks\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\PageHooks\PageHookResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePageHooks extends ManageRecords
{
    protected static string $resource = PageHookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
