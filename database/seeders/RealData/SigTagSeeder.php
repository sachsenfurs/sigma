<?php

namespace Database\Seeders\RealData;

use App\Models\SigTag;
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
                'description' => "NSFW",
                'description_en' => "NSFW",
            ],
            [
                'name' => "dance",
                'description' => 'Laute Musik',
                'description_en' => 'Loud Music',
            ],
            [
                'name' => "signup",
                'description' => 'Anmeldung erforderlich',
                'description_en' => 'Sign-up Required',
            ],
            [
                'name' => "fursuit",
                'description' => 'Fursuit-Event',
                'description_en' => 'Fursuit-Event',
            ],
        ]);

    }
}
