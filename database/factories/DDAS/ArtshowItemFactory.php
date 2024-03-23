<?php

namespace Database\Factories\DDAS;

use App\Models\DDAS\ArtshowArtist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DDAS\ArtshowItem>
 */
class ArtshowItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->realText(),
            'description_en' => $this->faker->realText(),
            'starting_bid' => $this->faker->numberBetween(0, 500),
            'charity_percentage' => $this->faker->numberBetween(0, 100),
            'additional_info' => $this->faker->text(),
            'image_file' => $this->faker->imageUrl(),
            'artshow_artist_id' => ArtshowArtist::factory(),
        ];
    }
}
