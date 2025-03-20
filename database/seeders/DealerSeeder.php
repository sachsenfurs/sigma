<?php

namespace Database\Seeders;

use App\Models\Ddas\Dealer;
use App\Models\Ddas\DealerTag;
use Illuminate\Database\Seeder;

class DealerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DealerTag::insert([
            [
                'name' => "NSFW",
                'name_en' => "NSFW",
            ],
            [
                'name' => "Merch",
                'name_en' => "Merch",
            ],
            [
                'name' => "Prints",
                'name_en' => "Prints",
            ],
            [
                'name' => "Plushies",
                'name_en' => "Plushies",
            ]
        ]);

        Dealer::factory(30)->create();

        foreach(Dealer::all() AS $dealer) {
            $dealer->tags()->sync(DealerTag::inRandomOrder()->limit(rand(0,3))->get());
        }
    }
}
