<?php

namespace App\Models\Scopes;

use App\Models\SigFilledForm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SigFormAccessScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Filter query to only show forms that the user has access to
        if(auth()->user()?->isAdmin())
            return;

        $roleIds = auth()->user()?->roles->pluck('id') ?? null;

        $relationKey = "userRoles";
        if($model instanceof SigFilledForm)
            $relationKey = "sigForm.userRoles";

        $builder->whereHas($relationKey, function(Builder $query) use ($roleIds) {
           return $query->whereIn('user_roles.id', $roleIds);
        });
    }
}
