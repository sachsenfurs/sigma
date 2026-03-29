<?php

namespace App\Filament\Resources\DepartmentInfos\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DepartmentInfos\DepartmentInfoResource;
use Filament\Resources\Pages\EditRecord;

class EditDepartmentInfo extends EditRecord
{
    protected static string $resource = DepartmentInfoResource::class;

    protected function getHeaderActions(): array {
        return [
            DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): ?string {
        return $this->previousUrl;
    }
}
