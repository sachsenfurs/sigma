<?php

namespace App\Notifications\TimetableEntry;

class ChangedFavoriteNotification extends ChangedNotification
{
    public static function getName(): string {
        return __("Favorite Event Changed");
    }

    protected function getSubject(): ?string {
        return "❗ " . __("Favorite Event Changed") . ": " . $this->entry->sigEvent->name_localized;
    }

    protected function getVia(): array {
        return ['database'];
    }
}
