<?php

namespace Database\Factories;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory
{
    protected $model = UserRole::class;

    public function definition(): array
    {
        $name = ucfirst($this->faker->unique()->words(2, true));

        return [
            'name' => $name,
            'name_en' => $name . ' EN',
            'fore_color' => '#333333',
            'border_color' => '#666666',
            'background_color' => '#E6E6E6',
            'registration_system_key' => (string) $this->faker->unique()->numberBetween(100, 999),
            'chat_activated' => true,
        ];
    }
}
