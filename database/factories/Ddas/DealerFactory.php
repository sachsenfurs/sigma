<?php

namespace Database\Factories\Ddas;

use App\Enums\Approval;
use App\Models\SigLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ddas\Dealer>
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
            'approval' => $this->faker->randomElement(Approval::cases())->value,
            'gallery_link' => $this->faker->url(),
            'icon_file' => $this->faker->imageUrl(width: 300, height: 300),
            'sig_location_id' => SigLocation::inRandomOrder()->first()->id,
            'user_id' => User::factory(),
            'additional_info' => $this->faker->realText(),
        ];
    }
}
