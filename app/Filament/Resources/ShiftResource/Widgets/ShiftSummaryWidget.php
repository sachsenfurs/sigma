<?php

namespace App\Filament\Resources\ShiftResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Query\Builder;

class ShiftSummaryWidget extends BaseWidget
{

    protected $listeners = [
        'refreshShifts' => '$refresh',
    ];

    protected function getTableHeading(): string|Htmlable|null {
        return __("Shift Summary");
    }

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table {
        return $table
            ->query(User::with(["userShifts.shift.type"])
                ->whereHas("userShifts")
                ->select('users.*')
                ->selectSub(function ($q) {
                    $q->from('shift_user')
                      ->join('shifts', 'shifts.id', '=', 'shift_user.shift_id')
                        ->join('shift_types', 'shift_types.id', '=', 'shifts.shift_type_id')
                        ->whereColumn('shift_user.user_id', 'users.id')
                        ->where(function(Builder $query) {
                            if(session('calendar_selected_user_role_id'))
                                return $query->where('shift_types.user_role_id', session('calendar_selected_user_role_id'));
                        })
                      ->whereColumn('shift_user.user_id', 'users.id')
                      ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, shifts.start, shifts.end)) / 60');
                }, 'total_hours')
                ->selectSub(function ($q) {
                    $q->from('shift_user')
                      ->join('shifts', 'shifts.id', '=', 'shift_user.shift_id')
                        ->join('shift_types', 'shift_types.id', '=', 'shifts.shift_type_id')
                        ->whereColumn('shift_user.user_id', 'users.id')
                        ->where(function(Builder $query) {
                            if(session('calendar_selected_user_role_id'))
                                return $query->where('shift_types.user_role_id', session('calendar_selected_user_role_id'));
                        })
                      ->whereColumn('shift_user.user_id', 'users.id')
                      ->selectRaw('COUNT(*)');
                }, 'user_shifts_count')

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
                    ->state(fn ($record) => round($record->total_hours, 2) . " h")
            ]);
    }
}
