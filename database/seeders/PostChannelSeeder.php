<?php

namespace Database\Seeders;

use App\Models\PostChannel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // test channel
        PostChannel::create([
            'channel_identifier' => -1001797974427,
            'language' => "de",
            'implementation' => "TelegramPostChannel::class",
        ]);
        PostChannel::create([
            'channel_identifier' => -1001228930181,
            'language' => "en",
            'implementation' => "TelegramPostChannel::class",
        ]);
    }
}
