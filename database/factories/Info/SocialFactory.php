<?php

namespace Database\Factories\Info;

use App\Models\Info\Enums\ShowMode;
use App\Models\Info\Social;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialFactory extends Factory
{
    protected $model = Social::class;

    public function definition(): array
    {
        $label = ucfirst($this->faker->words(2, true));

        return [
            'description' => $label,
            'description_en' => $label . ' EN',
            'link' => $this->faker->url(),
            'link_en' => $this->faker->url(),
            'link_name' => $label,
            'link_name_en' => $label . ' EN',
            'icon' => 'bi-globe',
            'image' => null,
            'image_en' => null,
            'show_on' => [ShowMode::SIGNAGE->value],
            'order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
