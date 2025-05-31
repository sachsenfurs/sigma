<?php

namespace App\Models\Traits;

use App\Settings\AppSettings;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait NameIdAsSlug {
    private function slugChecksum(): string {
        // checksum to avoid clicking links from past events
        return substr(md5(app(AppSettings::class)->event_start), 1, 4);
    }

    public function slug() : Attribute {
        return Attribute::make(function() {
            return $this->id.$this->slugChecksum()."-".urlencode(Str::slug($this->name));
        });
    }

    public function resolveRouteBinding($value, $field = null) {
        $parts      = explode($this->slugChecksum(), $value);
        $id         = $parts[0] ?? 0;

        if(!ctype_digit($id) AND str_contains($value, "-"))
            abort(404);

        return self::where($this->getTable().".id", $id)->orWhere($this->getTable().".name", $value)->firstOrFail();
    }

}
