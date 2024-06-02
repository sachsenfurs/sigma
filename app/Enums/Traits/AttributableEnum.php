<?php

namespace App\Enums\Traits;

trait AttributableEnum
{
    public function __call(string $method, array $arguments) {
        $reflection = new \ReflectionEnum(static::class);
        $attributes = $reflection->getCase($this->name)->getAttributes();
        $filtered = array_filter($attributes, fn(\ReflectionAttribute $attribute) => collect(explode("\\", $attribute->getName()))->last() == ucfirst($method));
        if (empty($filtered)) {
            return null;
        }
        return array_shift($filtered)->newInstance()->get();
    }
}
