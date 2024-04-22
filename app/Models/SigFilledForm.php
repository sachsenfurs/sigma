<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigFilledForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'form_data' => 'array'
    ];

    public function sigForm() {
        return $this->belongsTo(SigForm::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
