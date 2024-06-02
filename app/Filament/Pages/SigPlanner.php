<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource\Widgets\SigPlannerWidget;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\FilamentFullCalendarProvider;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Panel;
use Filament\Support\Enums\MaxWidth;

class SigPlanner extends Page
{
    protected static ?string $title = "Planner View";

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.resources.timetable-entry-resource.pages.sig-planner';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = SigPlanning::class;


    public function __construct() {
        // registering here instead of AdminPanelProvider because otherwise all fullcalendar-related queries get executed on every other page as well...
        FilamentFullCalendarProvider::registerPlugin(Filament::getPanel('admin'));
    }

    public static function canAccess(): bool {
        return auth()->user()->permissions()->contains('manage_sigs');
    }

    protected function getHeaderWidgets(): array {
        return [
            SigPlannerWidget::class,
        ];
    }

    public function getMaxContentWidth(): MaxWidth|string|null {
        return MaxWidth::Full;
    }
}
