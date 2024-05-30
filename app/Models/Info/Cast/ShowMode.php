<?php

namespace App\Models\Info\Cast;


use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class ShowMode implements Castable {

    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class implements CastsAttributes {
            public function get(Model $model, string $key, mixed $value, array $attributes): array {
                $modes = is_array($value) ? $value : json_decode($value);
                return collect($modes)->map(fn($m) => \App\Models\Info\Enum\ShowMode::tryFrom($m))->toArray();
            }

            public function set(Model $model, string $key, mixed $value, array $attributes): array {
                $modes = collect($value)->map(fn($m) => $m instanceof \App\Models\Info\Enum\ShowMode ? $m : \App\Models\Info\Enum\ShowMode::tryFrom($m));
                return [
                    $key => $modes->pluck("value")->toArray()
                ];
            }
        };
    }
}
