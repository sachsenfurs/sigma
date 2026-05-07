<?php

namespace Database\Factories\Ddas;

use App\Models\Ddas\DealerTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class DealerTagFactory extends Factory
{
    protected $model = DealerTag::class;

    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(2, true));

        return [
            'name' => $name,
            'name_en' => $name . ' EN',
        ];
    }
}
