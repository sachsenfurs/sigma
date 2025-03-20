<?php

namespace Database\Factories\Ddas;

use App\Enums\Approval;
use App\Models\Ddas\ArtshowArtist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'name' => Str::trim(collect([
                ucfirst($this->faker->randomElement([
                    'cute',
                    'sweet',
                    'angry',
                    'ugly',
                    'painted',
                    'aggressive',
                    'ambitious',
                    'brave',
                    'calm',
                    'delightful',
                    'eager',
                    'faithful',
                    'gentle',
                    'happy',
                    'nice',
                    'obedient',
                    'polite',
                    'proud',
                    'silly',
                    'thankful',
                    'victorious',
                    'wonderful',
                ])),
                Str::lower(Str::headline($this->faker->colorName())),
                $this->faker->randomElement([
                    'fox',
                    'wolf',
                    'dragon',
                    'yeen',
                    'tiger',
                    'protogen',
                    'husky',
                    'dog',
                    'cat',
                    'shep',
                    'raccoon'
                ])
            ])->join(" ")),
            'description' => $this->faker->realText(),
            'description_en' => $this->faker->realText(),
            'starting_bid' => $this->faker->numberBetween(0, 500),
            'charity_percentage' => $this->faker->numberBetween(0, 100),
            'additional_info' => $this->faker->text(),
            'image' => $this->faker->imageUrl(),
            'approval' => $this->faker->randomElement(Approval::class)->value,
            'artshow_artist_id' => ($this->faker->boolean() ? ArtshowArtist::inRandomOrder()->first()?->id : null) ?? ArtshowArtist::factory(),
        ];
    }
}
