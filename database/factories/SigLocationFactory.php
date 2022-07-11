<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SigLocation>
 */
class SigLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        return [
            'name' => $this->faker->city(),
            'render_ids' => $this->faker->randomElements(["poolArea", "hessenArea", "bayernArea", "sachsenArea", "mainstageArea1", "counterArea"], $this->faker->numberBetween(1,2)),
            'floor' => $this->faker->numberBetween(-1,2),
            'room' => "",
            'roomsize' => "",
            'seats' => $this->faker->numberBetween(1,20)*10,
        ];
    }
}
