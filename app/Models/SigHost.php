<?php

namespace App\Models;

use App\Models\Traits\HasSigEvents;
use App\Models\Traits\HasTimetableEntries;
use App\Models\Traits\NameIdAsSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SigHost extends Model
{
    use HasFactory, HasSigEvents, HasTimetableEntries, NameIdAsSlug;

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'hide' => "boolean",
    ];

    public function user() {
        return $this->belongsTo(User::class, "reg_id", "reg_id");
    }

    public function scopePublic($query) {
        return $query->where('hide', false)
            ->whereHas("sigEvents", function($query) {
                $query->whereHas("timeTableEntries", function($query) {
                   $query->where("hide", false);
                });
            });
    }

    public function avatar() : Attribute {
        return Attribute::make(
            get: fn() => ($this->user?->avatar ?? "")
        );
    }

}
