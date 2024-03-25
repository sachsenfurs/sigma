<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $permissionName)
 * @method static create(array $array)
 */
class Permission extends Model
{
    use HasFactory;

    /**
     * Protected fields in this model.
     *
     * @var array
     */
    protected $guarded = [];

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'user_role_permissions');
    }
}
