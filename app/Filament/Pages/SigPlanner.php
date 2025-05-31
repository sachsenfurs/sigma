<?php

namespace App\Filament\Pages;

use App\Filament\Clusters\SigPlanning;
use App\Filament\Resources\TimetableEntryResource\Widgets\SigPlannerWidget;
use App\Filament\Resources\TimetableEntryResource\Widgets\UnprocessedSigEvents;
use App\Filament\Traits\HasActiveIcon;
use App\Models\TimetableEntry;
use App\Providers\Filament\FilamentSigCalendarProvider;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class SigPlanner extends Page
{
    use HasActiveIcon;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static string $view = 'filament.resources.timetable-entry-resource.pages.sig-planner';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = SigPlanning::class;

    public function getHeading(): string|Htmlable {
        return __("Planner View");
    }
    public static function getNavigationLabel(): string {
        return __("Planner View");
    }

    public function __construct() {
        // registering here instead of AdminPanelProvider because otherwise all fullcalendar-related queries get executed on every other page as well...
        FilamentSigCalendarProvider::registerPlugin(Filament::getPanel('admin'));
    }

    public static function canAccess(): bool {
        return Gate::allows("viewAny", TimetableEntry::class);
    }

    protected function getHeaderWidgets(): array {
        return [
            UnprocessedSigEvents::class,
            SigPlannerWidget::class,
        ];
    }

    public function getMaxContentWidth(): MaxWidth|string|null {
        return MaxWidth::Full;
    }
}
