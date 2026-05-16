<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'lost_at' => "datetime",
        'found_at' => "datetime",
        'returned_at' => "datetime",
    ];

    public function scopeFound(Builder $query): Builder {
        return $query->where("status", "F")->orderBy("found_at", "desc");
    }

    public function scopeLost(Builder $query): Builder {
        return $query->where("status", "L")->orderBy("lost_at", "desc");
    }

    public function scopeReturned(Builder $query): Builder {
        return $query->where("status", "R");
    }
}
