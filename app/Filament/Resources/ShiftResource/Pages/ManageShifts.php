<?php

namespace App\Filament\Resources\ShiftResource\Pages;

use App\Filament\Resources\ShiftResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRecords;

class ManageShifts extends ManageRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            ShiftResource\Widgets\ShiftPlannerWidget::class,
        ];
    }
}
