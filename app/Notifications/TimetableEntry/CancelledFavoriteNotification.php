<?php

namespace App\Notifications\TimetableEntry;

class CancelledFavoriteNotification extends CancelledNotification
{
    public static function getName(): string {
        return __("Favorite Event Cancelled");
    }

    protected function getSubject(): ?string {
        return "❌ " . __("Favorite Event Cancelled") . ": " . $this->entry->sigEvent->name_localized;
    }

    protected function getVia(): array {
        return ['database'];
    }
}
