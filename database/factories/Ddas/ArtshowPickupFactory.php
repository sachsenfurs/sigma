<?php

namespace Database\Factories\Ddas;

use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\ArtshowPickup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtshowPickupFactory extends Factory
{
    protected $model = ArtshowPickup::class;

    public function definition(): array
    {
        return [
            'artshow_item_id' => ArtshowItem::factory(),
            'user_id' => User::factory(),
            'info' => $this->faker->sentence(),
        ];
    }
}
