<?php

namespace Database\Factories\DDAS;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DDAS\ArtshowArtist>
 */
class ArtshowArtistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'social' => $this->faker->url(),
            'user_id' => User::factory(),
        ];
    }
}
