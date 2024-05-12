<?php

namespace App\Models;

use App\Models\Scopes\SigFormAccessScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SigFilledForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'form_data' => 'array'
    ];

    protected $with = [
        'sigForm'
    ];

    protected static function booted() {
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