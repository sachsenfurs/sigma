<?php

namespace App\Models;

use App\Observers\UserRoleObserver;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(UserRoleObserver::class)]
class UserRole extends Model implements HasColor
{
    protected $guarded = [];
    public $timestamps = false;
    protected $with = [
        'permissions'
    ];

    public function users(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'user_user_roles'
        );
    }

    public function permissions(): HasMany {
       return $this->hasMany(UserRolePermission::class);
    }

    public function departmentInfos(): HasMany {
        return $this->hasMany(DepartmentInfo::class);
    }

    public function chats(): HasMany {
        return $this->hasMany(Chat::class);
    }

    public function scopeChattable(Builder $query) {
        $query->where("chat_activated", true);
    }

    public function getColor(): string|array|null {
        return Color::hex($this->background_color);
    }
}
