<?php

namespace App\Filament\Resources\UserRoles\Pages;

use App\Filament\Resources\UserRoles\UserRoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserRole extends CreateRecord
{
    protected static string $resource = UserRoleResource::class;
}
