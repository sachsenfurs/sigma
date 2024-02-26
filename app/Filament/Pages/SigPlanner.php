<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource\Widgets\SigPlannerWidget;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Enums\MaxWidth;

class SigPlanner extends Page
{
    protected static ?string $title = "Planner View";

    protected static ?int $navigationSort = -10;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.resources.timetable-entry-resource.pages.sig-planner';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = SigPlanning::class;

//    protected ?string $heading = "";

    protected function getHeaderWidgets(): array {
        return [
            SigPlannerWidget::class,
        ];
    }


    public function getMaxContentWidth(): MaxWidth|string|null {
        return MaxWidth::Full;
    }

}
