<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ShiftResource\Widgets\ShiftPlannerWidget;
use App\Filament\Traits\HasActiveIcon;
use App\Models\TimetableEntry;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Gate;

class ShiftPlanner extends Page
{
    use HasActiveIcon;
//    protected static ?int $navigationSort = 2;
//    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.resources.shift-resource.pages.shift-planner';
//    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
//    protected static ?string $cluster = ShiftPlanning::class;

    public function getHeading(): string|Htmlable {
        return __("Shift Planner");
    }
    public static function getNavigationLabel(): string {
        return __("Shift Planner");
    }

    public static function canAccess(): bool {
        return Gate::allows("viewAny", TimetableEntry::class);
    }

    protected function getHeaderWidgets(): array {
        return [
            ShiftPlannerWidget::class,
        ];
    }

    public function form(Form $form): Form {
        return $form;
    }

    public function getMaxContentWidth(): MaxWidth|string|null {
        return MaxWidth::Full;
    }
}
