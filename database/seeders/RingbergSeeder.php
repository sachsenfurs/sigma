<?php

namespace Database\Seeders;

use App\Models\SigLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RingbergSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'name' => "Herbert Roth Saal",
                'description' => "Main Stage",
                'description_en' => "Main Stage",
                'render_ids' => json_encode(["mainstageArea1", "mainstageArea2"]),
                'floor' => "0",
                'room' => "KE 003",
                'roomsize' => "572",
                'seats' => 850,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Headless Area",
                'render_ids' => json_encode(["mainstageArea1"]),
                "essential_description" => "Während Mainstage-Events hast du hier einen geschützten Bereich um dich zu erfrischen und deinen Head abzusetzen.",
                "essential_description_en" => "During Mainstage events you can use this area to refresh yourself and take off your head.",
                "essential" => true,
            ],
            [
                'name' => "Open Stage",
                'description' => "Tagungsfoyer unten",
                'description_en' => "Conference Foyer downstairs",
                'render_ids' => json_encode(["foyerArea"]),
                'floor' => "-1",
                'room' => "KE 001",
                'roomsize' => "",
                'seats' => 0,
                'show_default' => true,
            ],
            [
                'name' => "Brandenburg",
                'render_ids' => json_encode(["brandenburgArea"]),
                'floor' => "-1",
                'room' => "K 1.U 031",
                'roomsize' => "35",
                'seats' => 80,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Sachsen 1+2",
                'description' => "Fursuitlounge",
                'description_en' => "Fursuitlounge",
                'render_ids' => json_encode(["sachsenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 028/029",
                'roomsize' => "200",
                'seats' => 200,
                'infodisplay' => true,
                "essential_description" => "Die Fursuitlounge steht dir jeden Tag von 10:30 bis 1:30 Uhr zur Verfügung.\r\nHier kannst du dich erfrischen und deinen Suit trocknen.\r\nZutritt nur für Fursuiter. Ein Helfer ist gestattet.",
                "essential_description_en" => "The Fursuit lounge is available daily from 10:30am to 1:30am.\r\nYou can refresh yourself and dry your fursuit here.\r\nAccess for fursuiters and one assistant per fursuiter only.",
                "essential" => true,
            ],
            [
                'name' => "Hessen",
                'render_ids' => json_encode(["hessenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 025",
                'roomsize' => "102",
                'seats' => 100,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Niedersachsen",
                'render_ids' => json_encode(["niedersachsenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 024",
                'roomsize' => "50",
                'seats' => 50,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Saarland",
                'render_ids' => json_encode(["saarlandArea"]),
                'floor' => "-1",
                'room' => "K 1.U 023",
                'roomsize' => "51",
                'seats' => 50,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Thüringen",
                'render_ids' => json_encode(["thüringenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 022",
                'roomsize' => "52",
                'seats' => 50,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Bayern",
                'render_ids' => json_encode(["bayernArea"]),
                'floor' => "-1",
                'room' => "K 1.U 021",
                'roomsize' => "52",
                'seats' => 50,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Berlin",
                'description' => "Gamesroom",
                'description_en' => "Gamesroom",
                'render_ids' => json_encode(["gamesArea"]),
                'floor' => "-1",
                'room' => "K 1.U 052",
                'roomsize' => "68",
                'seats' => 50,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "Suhl",
                'render_ids' => json_encode(["holliArea"]),
                'floor' => "-1",
                'room' => "K 1.U 030",
                'roomsize' => "96",
                'seats' => 0,
                'infodisplay' => true,
                'show_default' => true,
            ],
            [
                'name' => "WC",
                'description' => "Toiletten",
                'description_en' => "Toilets",
                'render_ids' => json_encode(["toilet1Area", "toilet2Area", "babyArea", "disabledWC"]),
                'floor' => "-1",
                'room' => "K 1.U 035-042",
                'roomsize' => "0",
                'seats' => 0,
                'essential_description' => "Toiletten, Toilette für körperlich beeinträchtigte Personen, Wickelraum",
                'essential_description_en' => "Toilets, Toilet for handicapped people, Baby-Care Room",
                "essential" => true,
            ],
            [
                'name' => "WC",
                'description' => "Toiletten",
                'description_en' => "Toilets",
                'render_ids' => json_encode(["toilet3Area"]),
                'floor' => "0",
                'room' => "",
                'roomsize' => "0",
                'seats' => 0,
                'essential_description' => "Toiletten",
                'essential_description_en' => "Toilets",
                "essential" => true,
            ],
            [
                'name' => "Rasselbock",
                'render_ids' => json_encode(["rasselArea"]),
                'floor' => "0",
                'room' => "KE 015",
                'roomsize' => "97",
                'seats' => 0,
            ],
            [
                'name' => "Lobby",
                'description' => "Vor der Bar",
                'description_en' => "In front of the bar",
                'render_ids' => json_encode(["munchArea1"]),
                'floor' => "0",
            ],
            [
                'name' => "Lobby",
                'description' => "Vor Rasselbock (Reg)",
                'description_en' => "In front of Rasselbock (Reg)",
                'render_ids' => json_encode(["lobbyArea2"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'name_en' => "Outdoor Area",
                'description' => "Hinterer Parkplatz (Motorfurs)",
                'description_en' => "Rear parking lot (Motorfurs)",
                'render_ids' => json_encode(["parkplatzArea1", "parkplatzArea2"]),
                'floor' => "0",
            ],
            [
                'name' => "Feuerplatz",
                'name_en' => "Campfire Area",
                'render_ids' => json_encode(["feuerArea"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'name_en' => "Outdoor Area",
                'description' => "Volleyballfeld",
                'description_en' => "Volleyball field",
                'render_ids' => json_encode(["volleyArea"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'name_en' => "Outdoor Area",
                'description' => "BBQ Bereich",
                'description_en' => "BBQ area",
                'render_ids' => json_encode(["outdoorArea1"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'name_en' => "Outdoor Area",
                'description' => "Vor dem Hotel",
                'description_en' => "In front of the hotel",
                'render_ids' => json_encode(["outdoorArea2"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'name_en' => "Outdoor Area",
                'description' => "Hinten auf der Wiese",
                'description_en' => "Back on the lawn",
                'render_ids' => json_encode(["outdoorArea6"]),
                'floor' => "0",
            ],
            [
                'name' => "Pool",
                'render_ids' => json_encode(["poolArea"]),
                'floor' => "0",
            ],
            [
                'name' => "Pool & Sauna",
                'render_ids' => json_encode(["poolArea"]),
                'floor' => "0",
                "essential_description" => "Das kühle Nass steht dir täglich von 9 bis 24 Uhr zur Verfügung, für unsere Hotelgäste ist der Pool kostenfrei.\r\nZwischen 20 und 24 Uhr heizen wir mit Licht und Musik euch Wasserviechern ordentlich ein!\r\nDie Benutzung des Pools mit Inflas ist zwischen 14 und 17 Uhr gestattet.",
                "essential_description_en" => "The pool is available daily from 9am to midnight, for our hotel guests the pool is free of charge.\r\nBetween 8pm and midnight there will be music!\r\nInflas are permitted from 2pm to 5pm.",
                "essential" => true,
            ],
            [
                'name' => "Restaurant Philharmonie",
                'render_ids' => json_encode(["brekkyArea1", "brekkyArea2", "brekkyArea3", "brekkyArea4"]),
                'floor' => "0",
            ],
            [
                'name' => "Frühstück",
                'name_en' => "Breakfast",
                'render_ids' => json_encode(["brekkyArea1", "brekkyArea2", "brekkyArea3", "brekkyArea4"]),
                'floor' => "0",
                "essential_description" => "Täglich von 8:00 Uhr bis 12:00 Uhr",
                "essential_description_en" => "Eveyday from 8am to 12pm",
                "essential" => true,
            ],
            [
                'name' => "Bar",
                'render_ids' => json_encode(["barArea"]),
                'floor' => "0",
            ],
            [
                'name' => "4-Jahreszeiten",
                'render_ids' => json_encode(["seasonsArea"]),
                'floor' => "0",
                'show_default' => true,
                'infodisplay' => true,
            ],
            [
                'name' => "Rezeption",
                'name_en' => "Reception",
                'render_ids' => json_encode(["counterArea"]),
                'floor' => "0",
                "essential_description" => "Bei Fragen und Anliegen zum Hotel und eurem Zimmer bekommst du hier Hilfe.",
                "essential_description_en" => "If you have any questions or concerns about the hotel and your room, you can get help here.",
                "essential" => true,
            ],
            [
                'name' => "Kulisse",
                'render_ids' => json_encode([]),
                'floor' => "-2",
                'show_default' => true,
            ],
            [
                'name' => "Außenbereich",
                'name_en' => "Outdoor Area",
                'description' => "Hinterausgang an der Bar",
                'description_en' => "Back exit at the bar",
                'render_ids' => json_encode(["outdoorArea3"]),
                'floor' => "0",
            ],
            [
                'name' => "Leseraum",
                'name_en' => "Reading Room",
                'description' => '"Glaskasten" in der Lobby',
                'description_en' => "Glass office in the lobby",
                'render_ids' => json_encode(["smokersArea"]),
                'floor' => "0",
                'show_default' => true,
            ],
            [
                'name' => "Billardraum",
                'name_en' => "Billiard Room",
                'description' => 'Billardraum',
                'description_en' => "Billiard room",
                'render_ids' => json_encode(["gameArea"]),
                'floor' => "0",
            ]
        ];

        foreach($locations AS $location) {
            SigLocation::insert($location);
        }
    }
}
