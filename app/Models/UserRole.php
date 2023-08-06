<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    public static $perms = [
        'perm_manage_settings',
        'perm_manage_users',
        'perm_manage_events',
        'perm_manage_locations',
        'perm_manage_hosts',
    ];

    /**
     * Protected fields in this model.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * @param $newPerms
     * @return bool
     */
    public function setPerms($newPerms) {
        $updatePerms = [];
        foreach(self::$perms AS $perm) {
            $updatePerms[$perm] = boolval(in_array($perm, $newPerms));

        }
        return $this->update($updatePerms);
    }

    /**
     * Define the relationship between user-roles and their members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {
        return $this->hasMany(User::class);
    }
}
