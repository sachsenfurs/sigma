<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait NameIdAsSlug {
    public function slug() : Attribute {
        return Attribute::make(function() {
            return urlencode(Str::replace(" ", "-", Str::lower($this->name))."-".$this->id);
        });
    }

    public function resolveRouteBinding($value, $field = null) {
        $parts = explode("-", $value);
        $id = end($parts);

        $instances = self::where($this->getTable().".id", $id)->orWhere($this->getTable().".name", $value);

        return $instances->first();
    }
}
