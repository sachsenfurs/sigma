<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;

class SigTag extends Model
{
    protected $guarded = [];

    protected $appends = [
        'description_localized',
    ];

    public $timestamps = false;

    public function sigEvents(): BelongsToMany {
        return $this->belongsToMany(SigEvent::class);
    }

    public function getDescriptionLocalizedAttribute(): string {
        return App::getLocale() == "en" ? $this->description_en : $this->description;
    }

}
