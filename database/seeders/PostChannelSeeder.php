<?php

namespace Database\Seeders;

use App\Models\Post\Post;
use App\Models\Post\PostChannel;
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
        $channel1 = PostChannel::create([
            'channel_identifier' => -1001797974427,
            'language' => "de",
            'name' => "EAST Info DE Testkanal",
            'implementation' => "TelegramPostChannel::class",
        ]);
        $channel2 = PostChannel::create([
            'channel_identifier' => -1001228930181,
            'language' => "en",
            'name' => "EAST Info EN Test Channel",
            'implementation' => "TelegramPostChannel::class",
        ]);

        $post = Post::create([
            'user_id' => 1,
            'text' => "Hallo Welt",
            'text_en' => "Hello World",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $post->channels()->attach([
            [
                'post_id' => $post->id,
                'post_channel_id' => $channel1->id,
                'message_id' => 123123123,
            ]
        ]);

        $post->channels()->attach([
            [
                'post_id' => $post->id,
                'post_channel_id' => $channel2->id,
                'message_id' => 124124124,
            ]
        ]);

    }
}
