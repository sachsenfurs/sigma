<?php

namespace App\Observers;

use App\Models\DepartmentInfo;
use App\Notifications\DepartmentInfo\DepartmentInfoChangedNotification;
use App\Notifications\DepartmentInfo\DepartmentInfoCreatedNotification;
use App\Notifications\DepartmentInfo\DepartmentInfoDeletedNotification;

class DepartmentInfoObserver
{
    public function created(DepartmentInfo $departmentInfo): void {
        $departmentInfo->userRole->notify(
            new DepartmentInfoCreatedNotification($departmentInfo)
        );
    }

    public function updated(DepartmentInfo $departmentInfo): void {
        $departmentInfo->refresh();
        if($departmentInfo->isDirty(["additional_info", "user_role_id"])) {
            $departmentInfo->userRole->notify(
                new DepartmentInfoChangedNotification($departmentInfo, $departmentInfo->getOriginal("additional_info"))
            );
        }
    }

    public function deleted(DepartmentInfo $departmentInfo): void {
        $departmentInfo->userRole->notify(new DepartmentInfoDeletedNotification(
            $departmentInfo->sigEvent,
            $departmentInfo->additional_info
            )
        );
    }
}
