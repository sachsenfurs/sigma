<?php

namespace App\Filament\Resources\ShiftResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class ShiftSummaryWidget extends BaseWidget
{

    protected function getTableHeading(): string|Htmlable|null {
        return __("Shift Summary");
    }

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table {
        return $table
            ->query(User::with(["userShifts.shift.type"])
                ->withCount("userShifts")
                ->whereHas("userShifts")
                ->selectSub(function ($q) {
                    $q->from('shift_user')
                      ->join('shifts', 'shifts.id', '=', 'shift_user.shift_id')
                      ->whereColumn('shift_user.user_id', 'users.id')
                      ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, shifts.start, shifts.end)) / 60');
                }, 'total_hours')
            )
            ->columns([
                Tables\Columns\TextColumn::make('reg_id')
                    ->label(__("Reg Number"))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__("Name"))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_shifts_count')
                    ->label(__("Shift Count"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_hours')
                    ->label(__("Total Hours"))
                    ->sortable()
                    ->state(fn ($record) => round($record->userShifts->sum(fn ($s) => $s->shift->start->diffInHours($s->shift->end)), 2) . " h")
            ]);
    }
}
