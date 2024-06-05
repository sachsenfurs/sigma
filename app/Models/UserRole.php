<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class UserRole extends Model
{

    protected $guarded = [];
    protected $with = [
        'permissions'
    ];

    public function users(): HasManyThrough {
        return $this->hasManyThrough(
            User::class,
            UserUserRole::class,
            'user_role_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function permissions(): BelongsToMany {
        return $this->belongsToMany(
            Permission::class,
            'user_role_permissions'
        );
    }
}
