<?php

namespace App\Filament\Resources\DepartmentInfoResource\Pages;

use App\Filament\Resources\DepartmentInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartmentInfos extends ListRecords
{
    protected static string $resource = DepartmentInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
