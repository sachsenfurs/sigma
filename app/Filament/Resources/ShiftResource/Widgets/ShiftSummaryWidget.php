<?php

namespace App\Filament\Resources\ShiftResource\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ShiftSummaryWidget extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table {
        return $table
            ->query(User::with(["userShifts.shift.type"])
                ->withCount("userShifts")
                ->whereHas("userShifts")
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
            ]);
    }
}
