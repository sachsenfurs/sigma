<?php

namespace App\Models\Info;

use App\Models\Info\Enums\ShowMode;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Social extends Model
{
    protected $table = "info_socials";

    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'show_on' => Cast\ShowMode::class
    ];

    public function scopeSignage(\Illuminate\Database\Eloquent\Builder $query) {
        $query->orderBy("order", "ASC")->whereJsonContains("show_on", ShowMode::SIGNAGE);
    }

    public function scopeFooterIcon(\Illuminate\Database\Eloquent\Builder $query) {
        $query->orderBy("order", "ASC")->whereJsonContains("show_on", ShowMode::FOOTER_ICON);
    }
    public function scopeFooterText(\Illuminate\Database\Eloquent\Builder $query) {
        $query->orderBy("order", "ASC")->whereJsonContains("show_on", ShowMode::FOOTER_TEXT);
    }

    public function descriptionLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->description_en : $this->description
        );
    }
    public function linkNameLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->link_name_en : $this->link_name
        );
    }

    public function linkLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->link_en : $this->link
        );
    }

    public function imageUrl(): Attribute {
        return Attribute::make(
            get: fn() => $this->image ? Storage::disk("public")->url($this->image) : null
        );
    }

    public function imageUrlEn(): Attribute {
        return Attribute::make(
            get: fn() => $this->image_en ? Storage::disk("public")->url($this->image_en) : null
        );
    }


}
