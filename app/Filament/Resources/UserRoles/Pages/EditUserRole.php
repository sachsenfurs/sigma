<?php

namespace App\Filament\Resources\UserRoles\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\UserRoles\UserRoleResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUserRole extends EditRecord
{
    protected static string $resource = UserRoleResource::class;


    protected function handleRecordUpdate(Model $record, array $data): Model {
        // FIXME:
        // workaround because the filament repeater is currently broken and reloads the old relationship values after saving (permissions)
        $this->redirect(self::getUrl(['record' => $record]));

        return parent::handleRecordUpdate($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
