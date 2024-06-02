<?php

namespace App\Enums\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class AttributeProperty
{
    public function __construct(private mixed $value) {
        // Translate
        if(is_string($this->value))
            $this->value = __($this->value);
    }

    public function get(): mixed {
        return $this->value;
    }
}
