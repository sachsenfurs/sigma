<?php

namespace Database\Factories;

use App\Models\PageHook;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageHookFactory extends Factory
{
    protected $model = PageHook::class;

    public function definition(): array
    {
        return [
            'id' => 'hook-' . Str::lower((string) Str::uuid()),
            'content' => $this->faker->sentence(),
            'content_en' => $this->faker->sentence(),
            'html' => false,
            'description' => $this->faker->sentence(),
        ];
    }
}
