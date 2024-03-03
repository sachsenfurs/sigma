<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SigTag extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'description_localized',
    ];

    public $timestamps = false;

    public function sigEvent(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(SigEvent::class);
    }

    public function getDescriptionLocalizedAttribute(): string
    {
        return App::getLocale() == "en" ? $this->description_en : $this->description;
    }

}
