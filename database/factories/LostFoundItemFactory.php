<?php

namespace Database\Factories;

use App\Models\LostFoundItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class LostFoundItemFactory extends Factory
{
    protected $model = LostFoundItem::class;

    public function definition(): array
    {
        return [
            'lassie_id' => $this->faker->unique()->numberBetween(1000, 999999),
            'image_url' => $this->faker->imageUrl(),
            'thumb_url' => $this->faker->imageUrl(),
            'title' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['L', 'F', 'R']),
            'lost_at' => now()->subDay(),
            'found_at' => now(),
            'returned_at' => null,
        ];
    }
}
