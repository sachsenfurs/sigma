<?php

namespace App\Models;

use App\Enums\Permission;
use App\Enums\PermissionLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
