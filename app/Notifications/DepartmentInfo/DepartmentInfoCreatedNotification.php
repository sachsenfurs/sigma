<?php

namespace App\Notifications\DepartmentInfo;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Resources\DepartmentInfoResource;
use App\Models\DepartmentInfo;
use App\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepartmentInfoCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected DepartmentInfo $newInfo) {}

    // enforced channels
    protected function getVia(): array {
        return ['telegram', 'database'];
    }

    protected function getSubject(): ?string {
        return __("SIG Requirements for :sig Added", ['sig' => $this->newInfo->sigEvent->name_localized]) . ": " . $this->newInfo->userRole->name_localized;
    }

    protected function getLines(): array {
        return [
            __("Requirements for :sig have been added:", ['sig' => $this->newInfo->sigEvent->name_localized]),
            $this->newInfo->additional_info,

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
        return __("SIG Requirement Added");
    }

}
