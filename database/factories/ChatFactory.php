<?php

namespace Database\Factories;

use App\Enums\ChatStatus;
use App\Models\Chat;
use App\Models\User;
use Database\Factories\UserRoleFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatFactory extends Factory
{
    protected $model = Chat::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'subject' => ucfirst($this->faker->words(3, true)),
            'subjectable_type' => null,
            'subjectable_id' => null,
            'user_role_id' => UserRoleFactory::new(),
            'status' => ChatStatus::OPEN,
        ];
    }
}
