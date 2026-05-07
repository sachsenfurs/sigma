<?php

namespace Database\Factories;

use App\Models\NotificationRoute;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationRouteFactory extends Factory
{
    protected $model = NotificationRoute::class;

    public function definition(): array
    {
        return [
            'notification' => 'event_new',
            'notifiable_id' => User::factory(),
            'notifiable_type' => 'user',
            'channels' => ['mail'],
        ];
    }
}
