<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserRole extends Model
{

    protected $guarded = [];
    protected $with = [
        'permissions'
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'user_user_roles'
        );
    }

    public function permissions(): BelongsToMany {
        return $this->belongsToMany(
            Permission::class,
            'user_role_permissions'
        );
    }
}
