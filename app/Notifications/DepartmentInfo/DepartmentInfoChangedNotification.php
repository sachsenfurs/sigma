<?php

namespace App\Notifications\DepartmentInfo;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\DepartmentInfoResource;
use App\Models\DepartmentInfo;
use App\Models\TimetableEntry;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Telegram\TelegramBase;
use NotificationChannels\Telegram\TelegramMessage;

class DepartmentInfoChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected DepartmentInfo $newInfo, protected string $oldAdditionalInfo) {}

    // enforced channels
    protected function getVia(): array {
        return ['telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return __("SIG Requirements for :sig Updated", ['sig' => $this->newInfo->sigEvent->name_localized]) . ": " . $this->newInfo->userRole->name_localized;
    }

    protected function getLines(): array {
        return [
            __("The requirements for :sig have been changed:", ['sig' => $this->newInfo->sigEvent->name_localized]),
            $this->newInfo->additional_info,
            "",
            __("Previous requirements:"),
            $this->oldAdditionalInfo
        ];
    }

    public function toTelegram(object $notifiable): TelegramBase {
        return TelegramMessage::create()
            ->line("*".__("The requirements for :sig have been changed:", ['sig' => $this->newInfo->sigEvent->name_localized])."*")
            ->line($this->cleanMarkdown($this->newInfo->additional_info))
            ->line("")
            ->line("*".__("Previous requirements:")."*")
            ->line($this->cleanMarkdown($this->oldAdditionalInfo))
            ->button($this->getAction(), $this->getActionUrl());
    }

    protected function getAction(): ?string {
        return __("View Details");
    }

    protected function getActionUrl(): string {
        return DepartmentInfoResource::getUrl("index");
    }

    public static function userSetting(): bool {
        return auth()->user()->hasPermission(Permission::MANAGE_EVENTS, PermissionLevel::READ);
    }

    public static function getName(): string {
        return __("SIG Requirement Updated");
    }

    public function shouldSend(): bool {
        return $this->newInfo->sigEvent->timetableEntries->filter(fn(TimetableEntry $entry) => $entry->start->isFuture())->count() > 0;
    }

}
