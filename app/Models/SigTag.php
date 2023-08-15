<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SigTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function sigEvent() {
        return $this->belongsToMany(SigEvent::class);
    }

    public function getDescriptionLocalizedAttribute() {
        return App::getLocale() == "en" ? $this->description_en : $this->description;
    }

}
