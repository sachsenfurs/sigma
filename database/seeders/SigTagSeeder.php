<?php

namespace Database\Seeders;

use App\Models\SigTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SigTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SigTag::insert([
            [
                'name' => "NSFW",
                'description' => "NSFW Event",
            ],
            [
                'name' => "dance",
                'description' => 'Loud Music, Flashing Lights'
            ],
            [
                'nane' => "signup",
                'description' => 'Anmeldung erforderlich'
            ]
        ]);
    }
}
