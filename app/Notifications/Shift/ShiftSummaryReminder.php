<?php

namespace App\Notifications\Shift;

use App\Enums\Permission;
use App\Filament\Resources\ShiftResource;
use App\Models\UserShift;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;

class ShiftSummaryReminder extends Notification
{
    use Queueable;

    public function __construct(protected Collection $userShifts) {
        $this->setVia(['telegram']);
    }

    public static function getName(): string {
        return __("Daily Shift Summary");
    }

    protected function getSubject(): ?string {
        return __('Your shift summary for today');
    }

    protected function getLines(): array {
        $lines  = [
            __("Good morning! Here's your shift summary:"),
            ""
        ];

        $shifts = $this->userShifts->map(function(UserShift $userShift) {
            return " " . $userShift->shift->start->translatedFormat("H:i") . "-" . $userShift->shift->end->translatedFormat("H:i") . " - "
                . $userShift->shift->type->name
                . " (" . $userShift->shift->type->userRole->name_localized . ")";
        });

        return [...$lines, ...$shifts];
    }

    protected function getAction(): ?string {
        return __("View Shift");
    }

    protected function getActionUrl(): string {
        return ShiftResource::getUrl('index');
    }

    public static function userSetting(): bool {
        return auth()->user()->hasPermission(Permission::MANAGE_SHIFTS);
    }
}
