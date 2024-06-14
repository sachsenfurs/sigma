<?php

namespace App\Models;

use App\Models\Scopes\SigFormAccessScope;
use App\Observers\SigFilledFormObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(SigFilledFormObserver::class)]
class SigFilledForm extends Model
{
    protected $guarded = [];

    protected $casts = [
        'form_data' => 'array'
    ];

    protected $with = [
        'sigForm'
    ];

    protected static function booted(): void {
        static::addGlobalScope(new SigFormAccessScope);
    }

    public function sigForm(): BelongsTo
    {
        return $this->belongsTo(SigForm::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
