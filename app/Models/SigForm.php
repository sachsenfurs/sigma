<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SigForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'form_definition' => 'array'
    ];

    public function getNameLocalizedAttribute()
    {
        return App::getLocale() == 'en' ? ($this->name_en ?? $this->name) : $this->name;
    }

    public function sigEvent() {
        return $this->belongsTo(SigEvent::class);
    }

    public function sigFilledForms() {
        return $this->hasMany(SigFilledForm::class);
    }

    public function userRoles() {
        return $this->belongsToMany(UserRole::class);
    }

    public function resolveRouteBinding($value, $field = null) {
        $parts = explode('-', $value);
        $id = end($parts);

        $instances = self::where($this->getTable() . '.id', $id)->orWhere($this->getTable() . '.slug', $value);

        return $instances->first();
    }
}
