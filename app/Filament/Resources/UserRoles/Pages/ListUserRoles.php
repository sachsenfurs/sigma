<?php

namespace App\Filament\Resources\UserRoles\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\UserRoles\UserRoleResource;
use Filament\Resources\Pages\ListRecords;

class ListUserRoles extends ListRecords
{
    protected static string $resource = UserRoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
