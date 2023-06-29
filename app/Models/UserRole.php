<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    /**
     * Protected fields in this model.
     * 
     * @var array
     */
    protected $guarded = [];

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
