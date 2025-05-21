<?php

namespace Database\Seeders;

use App\Models\Post\Post;
use App\Models\Post\PostChannel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channels = [];
        // test channel
        $channels[] = PostChannel::create([
            'channel_identifier' => -1001797974427,
            'language' => "de",
            'name' => "EAST Info DE Testkanal",
            'implementation' => "TelegramPostChannel::class",
        ]);
        $channels[] = PostChannel::create([
            'channel_identifier' => -1001228930181,
            'language' => "en",
            'name' => "EAST Info EN Test Channel",
            'implementation' => "TelegramPostChannel::class",
        ]);

        File::copy(public_path("images/logo.png"), storage_path("app/public/logo.png"));
        Post::create([
            'user_id' => 1,
            'text' => "Schon neugierig, welches Programm euch auf der EAST geboten wird? 👀\nUnser Programmheft ist nun online, viel Spaß beim Stöbern, wir sehen uns auf dem Ringberg! \n\n▶️ https://sigma.sachsenfurs.de/",
            'text_en' => "Curious about what program will be offered at the EAST? 👀\nOur program booklet is now online, have fun browsing, see you on the Ringberg!\n\n▶️ https://sigma.sachsenfurs.de/",
            'image' => 'logo.png',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ])->channels()->attach($channels, ['message_id' => 1234567890]);

        Post::create([
            'user_id' => 1,
            'text' => "Dieses Jahr wird es auch eine Bar im Außenbereich geben.  Komm vorbei und genieße erfrischende Longdrinks, Softdrinks und kühles Bier unter freiem Himmel. 🍹🍸\n\nBitte denk daran, dass du an der Bar nur mit Bargeld oder per Zimmerrechnung zahlen kannst.\n\nWir freuen uns auf dich!",
            'text_en' => "This year there will also be an outdoor bar.  Come along and enjoy refreshing long drinks, soft drinks and cold beer in the open air.\n\nPlease remember that you can only pay at the bar with cash or by room bill.\n\nWe look forward to seeing you!",
            'created_at' => now()->subHours(4),
            'updated_at' => now()->subHours(4),
        ])->channels()->attach($channels, ['message_id' => 1234567890]);

        @copy(Storage::disk("local")->path("") . "../../database/seeders/RealData/Resources/photo.jpg", Storage::disk("local")->path("public/example.jpg"));
        Post::create([
            'user_id' => 1,
            'text' => "Liebe Gäste,\n\nwir möchten euch daran erinnern, bitte darauf zu achten, keinen Müll rund um das Hotelgelände liegen zu lassen. Es liegt in unserer gemeinsamen Verantwortung, die Umgebung sauber und schön zu halten.\n\nIndem wir unseren Abfall richtig entsorgen, tragen wir nicht nur zur Sauberkeit bei, sondern zeigen auch Respekt gegenüber der Natur, dem Ringberg und unseren Mitmenschen. Schließlich möchten wir alle in einer angenehmen und gepflegten Umgebung unseren Aufenthalt genießen.\n\nDanke für eure Vernunft und euer Verständnis!",
            'text_en' => "Dear guests,\n\nWe would like to remind you to please take care not to leave any garbage lying around the hotel grounds. It is our joint responsibility to keep the surroundings clean and beautiful.\n\nBy disposing of our waste properly, we not only contribute to cleanliness, but also show respect for nature, the Ringberg and our fellow human beings. After all, we all want to enjoy our stay in a pleasant and well-kept environment.\n\nThank you for your common sense and understanding!",
            'image' => "example.jpg",
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ])->channels()->attach($channels, ['message_id' => 1234567890]);

        Post::create([
            'user_id' => 1,
            'text' => "Verlorene Sachen bitte  in der Leitstelle /ConOps baldig abholen!\nIhr habt heute noch die Möglichkeit eure verlorene Sachen aufzuholen.\n",
            'text_en' => "Please collect your lost items from the control center /ConOps as soon as possible!\nYou still have the opportunity to pick up your lost items today.\n",
            'created_at' => now()->subHours(),
            'updated_at' => now()->subHours(),
        ])->channels()->attach($channels, ['message_id' => 1234567890]);

        Post::create([
            'user_id' => 1,
            'text' => "Wir wollen wissen, wie es euch gefallen hat! 🎉 \nNehmt euch doch kurz Zeit und füllt unser Feedback-Formular aus: [sachsenfurs.de/feedback](http://sachsenfurs.de/feedback). Eure Meinung hilft uns, noch besser zu werden. \nDanke für eure Unterstützung! 🙌",
            'text_en' => "We want to know how you liked it! 🎉\nTake a moment to fill out our feedback form: [sachsenfurs.de/feedback](http://sachsenfurs.de/feedback). Your opinion helps us to become even better. Thank you for your support! 🙌",
            'created_at' => now(),
            'updated_at' => now(),
        ])->channels()->attach($channels, ['message_id' => 1234567890]);

    }
}
