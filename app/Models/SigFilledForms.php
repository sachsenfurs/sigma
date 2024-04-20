<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigFilledForms extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'form_data' => 'array'
    ];

    public function sigForms() {
        return $this->belongsTo(SigForms::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
