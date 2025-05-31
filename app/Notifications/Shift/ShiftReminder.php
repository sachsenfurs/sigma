<?php

namespace App\Notifications\Shift;

use App\Enums\Necessity;
use App\Enums\Permission;
use App\Filament\Resources\ShiftResource;
use App\Models\Shift;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;

class ShiftReminder extends Notification
{
    use Queueable;

    public function __construct(protected Shift $shift) {
        $this->setVia(['telegram']);
    }

    public static function getName(): string {
        return __("Shift Reminder");
    }

    protected function getSubject(): ?string {
        return __('Your :department-shift ":type" starts in :min minutes!', [
            'type' => $this->shift->type->name,
            'department' => $this->shift->type->userRole->name_localized,
            'min' => (int) round($this->shift->start->diffInMinutes()*-1)
        ]);
    }

    protected function getLines(): array {
        $lines = [];

        if($this->shift->info) {
            $lines[] = $this->shift->info;
            $lines[] = "";
        }

        $lines[] = "🕗 " . __("Time") . ": " . $this->shift->start->translatedFormat("H:i") . " - " . $this->shift->end->translatedFormat("H:i");

        if($this->shift->sigLocation)
            $lines[] = "📍 " . __("Location") . ": " . $this->shift->sigLocation->name_localized;

        $lines[] = match($this->shift->necessity) {
            Necessity::OPTIONAL => "🟢",
            Necessity::NICE_TO_HAVE => "🟡",
            Necessity::MANDATORY => "🔴",
            default => "",
        } . " " . __("Necessity").": " . $this->shift->necessity->name();


        return $lines;
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
