<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigHost extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'hide' => "boolean",
    ];

    public function sigEvents() {
        return $this->hasMany(SigEvent::class);
    }

}
