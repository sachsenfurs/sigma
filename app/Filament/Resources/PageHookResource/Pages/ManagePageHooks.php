<?php

namespace App\Filament\Resources\PageHookResource\Pages;

use App\Filament\Resources\PageHookResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePageHooks extends ManageRecords
{
    protected static string $resource = PageHookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
