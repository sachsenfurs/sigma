<?php

namespace Database\Factories;

use App\Models\SigHost;
use App\Models\SigLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SigEvent>
 */
class SigEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $datetime = $this->faker->dateTime();
        return [
            'name' => $this->faker->text(25),
            'sig_host_id' => SigHost::all()->random(),
            'default_language' => $this->faker->randomElement(['de', 'en']),
            'languages' => $this->faker->randomElement([["de"], ["de","en"]]),
            'description' => $this->faker->realText(),
            'sig_location_id' => SigLocation::all()->random(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->randomElement([$this->faker->dateTime(), $datetime]),
        ];
    }
}
