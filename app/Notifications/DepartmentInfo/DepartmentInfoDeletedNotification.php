<?php

namespace App\Notifications\DepartmentInfo;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\DepartmentInfoResource;
use App\Models\SigEvent;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepartmentInfoDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected SigEvent $sigEvent, protected string $oldAdditionalInfo) {}

    // enforced channels
    protected function getVia(): array {
        return ['telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return __("SIG Requirements for :sig Removed", ['sig' => $this->sigEvent->name_localized]);
    }

    protected function getLines(): array {
        return [
            __("Requirements for :sig have been removed", ['sig' => $this->sigEvent->name_localized]),
            __("Previous requirements:"),
            $this->oldAdditionalInfo
        ];
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
        return __("SIG Requirement Removed");
    }

}
