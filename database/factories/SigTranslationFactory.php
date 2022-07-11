<?php

namespace Database\Factories;

use App\Models\SigEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SigTranslation>
 */
class SigTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'sig_event_id' => null,
            'language' => $this->faker->randomElement(["de", "en"]),
            'name' => $this->faker->text(25),
            'description' => $this->faker->realText(),
        ];
    }
}
