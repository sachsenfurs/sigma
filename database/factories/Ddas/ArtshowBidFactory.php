<?php

namespace Database\Factories\Ddas;

use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtshowBidFactory extends Factory
{
    protected $model = ArtshowBid::class;

    public function definition(): array
    {
        return [
            'artshow_item_id' => ArtshowItem::factory(),
            'user_id' => User::factory(),
            'value' => $this->faker->numberBetween(10, 100),
        ];
    }
}
