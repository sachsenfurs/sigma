<?php

namespace Database\Factories\Post;

use App\Models\Post\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'text' => $this->faker->paragraph(),
            'text_en' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'image' => null,
        ];
    }
}
