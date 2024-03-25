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
            'languages' => $this->faker->randomElement([["de"], ["en"], ["de","en"]]),
            'description' => $this->faker->realText(),
            'description_en' => $this->faker->realText(),
            'additional_infos' => $this->faker->realText(),
            'sig_location_id' => SigLocation::all()->random(),
            'fursuit_support' => $this->faker->randomElement(["0", "1"]),
            'medic' => $this->faker->randomElement(["0", "1"]),
            'security' => $this->faker->randomElement(["0", "1"]),
            'other_stuff' => $this->faker->randomElement(["0", "1"]),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->randomElement([$this->faker->dateTime(), $datetime]),
        ];
    }
}
