<?php

namespace Database\Factories;

use App\Models\SigHost;
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
            'name_en' => $this->faker->text(25),
            'sig_host_id' => SigHost::all()->random(),
            'languages' => $this->faker->randomElement([["de"], ["en"], ["de","en"]]),
            'description' => $this->faker->realText(),
            'description_en' => $this->faker->realText(),
            'additional_infos' => $this->faker->realText(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->randomElement([$this->faker->dateTime(), $datetime]),
        ];
    }
}
