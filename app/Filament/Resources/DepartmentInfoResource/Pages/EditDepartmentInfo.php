<?php

namespace App\Filament\Resources\DepartmentInfoResource\Pages;

use App\Filament\Resources\DepartmentInfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentInfo extends EditRecord
{
    protected static string $resource = DepartmentInfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
