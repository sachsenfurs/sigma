<?php

namespace Database\Factories;

use App\Enums\Approval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SigEvent>
 */
class SigEventFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $datetime = $this->faker->dateTime();
        $event_name = $this->randomName();
        return [
            'name' => $event_name[0],
            'name_en' => $event_name[1],
            'languages' => $this->faker->randomElement([["de"], ["en"], ["de","en"]]),
            'duration' => $this->faker->numberBetween(1,10) * 30,
            'approval' => $this->faker->randomElement(Approval::cases()),
            'description' => $this->faker->realText(),
            'description_en' => $this->faker->realText(),
            'additional_info' => $this->faker->realText(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->randomElement([$this->faker->dateTime(), $datetime]),
        ];
    }

    private static $usedIndex = 0;
    public function randomName() {
        // thank you, ChatGPT <3
        $events = [
            ["Mähnen-Styling Workshop", "Mane Styling Workshop"],
            ["DIY Schweif- & Ohren-Basteln", "DIY Tail & Ear Crafting"],
            ["Furry Yoga & Stretching", "Furry Yoga & Stretching"],
            ["Leuchtstab-Tanzparty", "Glowstick Dance Party"],
            ["Heul den Mond an - Karaoke", "Howl at the Moon Karaoke"],
            ["Lucha Furry Wrestling", "Lucha Furry Wrestling"],
            ["Suit Up - Fursuit Pflege", "Suit Up - Fursuit Maintenance"],
            ["Entkomme dem Labyrinth", "Escape the Labyrinth"],
            ["Furry Speed-Dating", "Furry Speed-Dating"],
            ["Retro Arcade Nacht", "Retro Arcade Night"],
            ["DIY Plüschtier-Workshop", "DIY Plushie Workshop"],
            ["Fuchs vs. Wolf - Quiz-Battle", "Fox vs. Wolf - Quiz Battle"],
            ["Furry Sketch Roulette", "Furry Sketch Roulette"],
            ["Silent Disco Paw-ty", "Silent Disco Pawty"],
            ["Kuscheltier-Krankenhaus", "Plushy Hospital"],
            ["Dungeons & Doggos RPG-Session", "Dungeons & Doggos RPG Session"],
            ["Pfotenabdruck-Keksbäckerei", "Pawprint Cookie Workshop"],
            ["Wuff & Fluff - Furry Fitness", "Wuff & Fluff - Furry Fitness"],
            ["Neon Nachtwanderung", "Neon Night Walk"],
            ["Dschungel-Trommelkreis", "Jungle Drum Circle"],
            ["Gitarren-Jam im Park", "Guitar Jam in the Park"],
            ["Verschwörungstheorien: Furry Edition", "Conspiracy Theories: Furry Edition"],
            ["Pfotenmalerei Kunst-Session", "Paw-Painting Art Session"],
            ["Schweif-Wackel-Wettbewerb", "Tail Wagging Contest"],
            ["Murder Mystery: Wer hat den Schweif gestohlen?", "Murder Mystery: Who Stole the Tail?"],
            ["Furry Parkour Challenge", "Furry Parkour Challenge"],
            ["Kuscheldecken-Fest", "Blanket Fort Festival"],
            ["Furry Comedy Open Mic", "Furry Comedy Open Mic"],
            ["Laser-Tag: Katzen vs. Hunde", "Laser Tag: Cats vs. Dogs"],
            ["Furry Escape Room", "Furry Escape Room"],
            ["Märchenstunde mit Fluff", "Storytime with Fluff"],
            ["Fellfarben- & Musterkunde", "Fur Colors & Patterns Workshop"],
            ["Mondschein-Spaziergang", "Moonlight Walk"],
            ["Meme & Shitpost Panel", "Meme & Shitpost Panel"],
            ["Fluffy Science - Wissenschaft für Furries", "Fluffy Science - Science for Furries"],
            ["Furry Brettspiel-Abend", "Furry Board Game Night"],
            ["Fuchsbau-Kissenburg bauen", "Fox Den Pillow Fort Building"],
            ["Soundtrack deines Fursonas", "The Soundtrack of Your Fursona"],
            ["Furry Schattentheater", "Furry Shadow Theater"],
            ["Seifenblasen-Zauber", "Bubble Magic"],
            ["Tanzkurs: Die Kunst des Schwanz-Schwingens", "Dance Class: The Art of Tail Swinging"],
            ["Kreatives Knurren & Brummen", "Creative Growling & Purring"],
            ["Werwolf Live-Rollenspiel", "Werewolf Live Roleplay"],
            ["Naturklänge & Meditation", "Nature Sounds & Meditation"],
            ["Impro-Theater für Furries", "Improv Theater for Furries"],
            ["Schleim-Workshop: Fluffy Slime", "Slime Workshop: Fluffy Slime"],
            ["Pfoten- und Krallen-Pflege", "Paw & Claw Care Workshop"],
            ["Furry Chillout Lounge", "Furry Chillout Lounge"],
            ["Zeichne deine Fursona in 5 Stilen", "Draw Your Fursona in 5 Styles"],
            ["Kuschelige Gute-Nacht-Geschichte", "Cozy Bedtime Story"],
        ];
        if(self::$usedIndex >= count($events))
            self::$usedIndex = 0;
        $event = $events[self::$usedIndex];
        self::$usedIndex += 1;
        return $event;
    }
}
