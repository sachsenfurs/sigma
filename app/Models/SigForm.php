<?php

namespace App\Models;

use App\Models\Scopes\SigFormAccessScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class SigForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'form_definition' => 'array'
    ];

    protected static function booted() {
        static::addGlobalScope(new SigFormAccessScope);
    }

    public function getNameLocalizedAttribute()
    {
        return App::getLocale() == 'en' ? ($this->name_en ?? $this->name) : $this->name;
    }

    public function sigEvent(): BelongsTo
    {
        return $this->belongsTo(SigEvent::class);
    }

    public function sigFilledForms(): HasMany
    {
        return $this->hasMany(SigFilledForm::class);
    }

    public function userRoles(): BelongsToMany
    {
        return $this->belongsToMany(UserRole::class, 'sig_form_user_roles');
    }

    public function resolveRouteBinding($value, $field = null) {
        $parts = explode('-', $value);
        $id = end($parts);

        $instances = self::where($this->getTable() . '.id', $id)->orWhere($this->getTable() . '.slug', $value);

        return $instances->first();
    }
}
