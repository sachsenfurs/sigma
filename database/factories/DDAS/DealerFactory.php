<?php

namespace Database\Factories\DDAS;

use App\Models\SigLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DDAS\Dealer>
 */
class DealerFactory extends Factory
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
            'info' => $this->faker->realText(),
            'info_en' => $this->faker->realText(),
            'gallery_link' => $this->faker->url(),
            'icon_file' => $this->faker->imageUrl(),
            'sig_location_id' => SigLocation::inRandomOrder()->first()->id,
            'user_id' => User::factory(),
        ];
    }
}
