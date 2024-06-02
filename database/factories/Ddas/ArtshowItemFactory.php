<?php

namespace Database\Factories\Ddas;

use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\Enums\Approval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddas\ArtshowItem>
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
            'image' => $this->faker->imageUrl(),
            'approval' => $this->faker->randomElement(Approval::class)->value,
            'artshow_artist_id' => ArtshowArtist::factory(),
        ];
    }
}
