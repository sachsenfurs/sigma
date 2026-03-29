<?php

namespace App\Filament\Resources\TimetableEntryResource\Pages;

use Filament\Actions\DeleteAction;
use Closure;
use Filament\Schemas\Components\Grid;
use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\SigEventResource\RelationManagers\SigTimeslotsRelationManager;
use App\Filament\Resources\TimetableEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class EditTimetableEntry extends EditRecord
{
    protected static string $resource = TimetableEntryResource::class;

    protected static ?string $cluster = SigPlanning::class;

    protected function getHeaderActions(): array {
        return [
            DeleteAction::make(),
        ];
    }

    public function getHeading(): string {
        return __('Manage Event Schedule');

    }

    public function getRelationManagers(): array {
        return $this->record->sigEvent->reg_possible ? [
            SigTimeslotsRelationManager::class,
        ] : [];
    }

    public static function getEditAction(): Closure {
        return function (Model $record, array $data) {
            return self::handleUpdate($record, $data);
        };
    }
    protected function handleRecordUpdate(Model $record, array $data): Model {
        return self::handleUpdate($record, $data);
    }

    public static function handleUpdate(Model $record, array $data): Model {
        // If the reset_update flag is set, we will reset the updated_at timestamp
        if ($data['reset_update'] ?? false) {
            $record->updated_at = $record->created_at;
        }
        unset($data['reset_update']);

        // If the send_update flag is set, we will update the timestamps
        $record->timestamps = ($data['send_update'] ?? false);
        unset($data['send_update']);

        if ($record->timestamps)
            $record->update($data);
        else
            $record->updateQuietly($data);

        return $record;
    }

//    public function getSchema(string $name = ""): ?Schema {
//        return Schema::make()
//                ->columns(2)
//                ->schema(TimetableEntryResource::getSchema());
//    }
}
