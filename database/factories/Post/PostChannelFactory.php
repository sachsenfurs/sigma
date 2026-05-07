<?php

namespace Database\Factories\Post;

use App\Models\Post\PostChannel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostChannelFactory extends Factory
{
    protected $model = PostChannel::class;

    public function definition(): array
    {
        return [
            'channel_identifier' => $this->faker->unique()->numberBetween(100000, 999999),
            'test_channel_identifier' => $this->faker->numberBetween(100000, 999999),
            'info' => $this->faker->sentence(),
            'name' => ucfirst($this->faker->words(2, true)),
            'language' => 'de',
            'implementation' => 'TelegramPostChannel::class',
            'default' => true,
        ];
    }
}
