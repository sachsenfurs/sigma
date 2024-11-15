<?php

namespace App\Models\Ddas;

use App\Enums\Approval;
use App\Models\SigLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Dealer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'info_localized'
    ];


    protected $casts = [
        'approval' => Approval::class,
    ];

    public function scopeApproved(Builder $query) {
        $query->where("approval", "=", Approval::APPROVED->value);
    }

    public function user(): BelongsTo|null {
        return $this->belongsTo(User::class);
    }

    public function sigLocation(): BelongsTo|null {
        return $this->belongsTo(SigLocation::class);
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(DealerTag::class);
    }

    public function infoLocalized(): Attribute {
        return Attribute::make(
            get: fn() => App::getLocale() == "en" ? $this->info_en : $this->info
        );
    }

    public function iconFileUrl(): Attribute {
        return Attribute::make(
            get: fn() => $this->icon_file ? Str::startsWith($this->icon_file, "http") ? $this->icon_file : Storage::url($this->icon_file) : ""
        );
    }
    public function galleryLinkName(): Attribute {
        return Attribute::make(
            get: fn() => parse_url($this->gallery_link, PHP_URL_HOST)
        );
    }

}
