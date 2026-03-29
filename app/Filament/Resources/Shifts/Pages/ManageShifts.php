<?php

namespace App\Filament\Resources\Shifts\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Shifts\Widgets\ShiftPlannerWidget;
use App\Filament\Resources\Shifts\Widgets\ShiftSummaryWidget;
use App\Filament\Resources\Shifts\ShiftResource;
use Filament\Resources\Pages\ManageRecords;

class ManageShifts extends ManageRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            ShiftPlannerWidget::class,
        ];
    }

    protected function getFooterWidgets(): array {
        return [
            ShiftSummaryWidget::class,
        ];
    }
}
