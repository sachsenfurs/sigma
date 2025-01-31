<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Observers\UserRolePermissionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(UserRolePermissionObserver::class)]
class UserRolePermission extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'permission' => Permission::class,
        'level' => PermissionLevel::class,
    ];

    public function role(): BelongsTo {
        return $this->belongsTo(UserRole::class);
    }
}
