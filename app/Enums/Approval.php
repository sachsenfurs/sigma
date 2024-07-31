<?php

namespace App\Enums;

use App\Enums\Attributes\Color;
use App\Enums\Attributes\Style;
use App\Enums\Attributes\Icon;
use App\Enums\Attributes\Name;
use App\Enums\Traits\AttributableEnum;
use Filament\Forms\Components\Radio;
use Filament\Support\Colors\Color as FilamentColor;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

enum Approval: int implements HasLabel, HasColor, HasIcon
{
    use AttributableEnum;

    #[Name('Pending Approval')]
    #[Color(FilamentColor::Yellow)]
    #[Icon('heroicon-o-question-mark-circle')]
    #[Style('bg-warning text-dark')]
    case PENDING = 0;

    #[Name('Approved')]
    #[Color(FilamentColor::Green)]
    #[Icon('heroicon-o-check-circle')]
    #[Style('bg-success')]
    case APPROVED = 1;

    #[Name('Rejected')]
    #[Color(FilamentColor::Red)]
    #[Icon('heroicon-o-x-circle')]
    #[Style('bg-danger')]
    case REJECTED = 2;


    /**
     * Filament translation
     * @return string|null
     */
    public function getLabel(): ?string {
        return $this->name();
    }

    /**
     * Filament color
     * @return string|array|null
     */
    public function getColor(): string|array|null {
        return $this->color();
    }

    /**
     * Filament icon
     * @return string|null
     */
    public function getIcon(): ?string {
        return $this->icon();
    }

    /**
     * Filament Bulk Action
     * @return Action
     */
    public static function getBulkAction(): BulkAction {
        return BulkAction::make("approval")
            ->icon("heroicon-o-question-mark-circle")
            ->translateLabel()
            ->form([
                Radio::make("approval")
                    ->options(Approval::class)
                    ->default(1),
            ])
            ->action(fn(array $data, Collection $records) => $records->each->update($data));
    }
}
