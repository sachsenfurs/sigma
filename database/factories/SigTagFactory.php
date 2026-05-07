<?php

namespace Database\Factories;

use App\Models\SigTag;
use Illuminate\Database\Eloquent\Factories\Factory;

class SigTagFactory extends Factory
{
    protected $model = SigTag::class;

    public function definition(): array
    {
        $name = strtolower($this->faker->unique()->bothify('tag-####'));

        return [
            'name' => $name,
            'description' => ucfirst($name) . ' description',
            'description_en' => ucfirst($name) . ' description en',
            'icon' => 'bi-star',
        ];
    }
}
