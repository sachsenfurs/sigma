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
                'name' => "Herbert Roth",
                'description' => "Mainstage",
                'render_ids' => json_encode(["mainstageArea1", "mainstageArea2"]),
                'floor' => "0",
                'room' => "KE 003",
                'roomsize' => "572",
                'seats' => 850,
            ],
            [
                'name' => "Foyer",
                'description' => "Tagungsfoyer unten",
                'render_ids' => json_encode(["foyerArea"]),
                'floor' => "-1",
                'room' => "KE 001",
                'roomsize' => "",
                'seats' => 0,
            ],
            [
                'name' => "Brandenburg",
                'render_ids' => json_encode(["brandenburgArea"]),
                'floor' => "-1",
                'room' => "K 1.U 031",
                'roomsize' => "35",
                'seats' => 80,
            ],
            [
                'name' => "Sachsen 1+2",
                'description' => "Fursuitlounge",
                'render_ids' => json_encode(["sachsenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 028/029",
                'roomsize' => "200",
                'seats' => 200,
            ],
            [
                'name' => "Hessen",
                'render_ids' => json_encode(["hessenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 025",
                'roomsize' => "102",
                'seats' => 100,
            ],
            [
                'name' => "Niedersachsen",
                'render_ids' => json_encode(["niedersachsenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 024",
                'roomsize' => "50",
                'seats' => 50,
            ],
            [
                'name' => "Saarland",
                'render_ids' => json_encode(["saarlandArea"]),
                'floor' => "-1",
                'room' => "K 1.U 023",
                'roomsize' => "51",
                'seats' => 50,
            ],
            [
                'name' => "Thüringen",
                'render_ids' => json_encode(["thüringenArea"]),
                'floor' => "-1",
                'room' => "K 1.U 022",
                'roomsize' => "52",
                'seats' => 50,
            ],
            [
                'name' => "Bayern",
                'render_ids' => json_encode(["bayernArea"]),
                'floor' => "-1",
                'room' => "K 1.U 021",
                'roomsize' => "52",
                'seats' => 50,
            ],
            [
                'name' => "Berlin",
                'description' => "Gamesroom",
                'render_ids' => json_encode(["gamesArea"]),
                'floor' => "-1",
                'room' => "K 1.U 052",
                'roomsize' => "68",
                'seats' => 50,
            ],
            [
                'name' => "Suhl",
                'render_ids' => json_encode(["holliArea"]),
                'floor' => "-1",
                'room' => "K 1.U 030",
                'roomsize' => "96",
                'seats' => 0,
            ],
            [
                'name' => "WC",
                'render_ids' => json_encode(["toilet1Area", "toilet2Area", "babyArea", "potatoArea"]),
                'floor' => "-1",
                'room' => "K 1.U 035-042",
                'roomsize' => "0",
                'seats' => 0,
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
                'render_ids' => json_encode(["munchArea1"]),
                'floor' => "0",
            ],
            [
                'name' => "Lobby",
                'description' => "Vor Rasselbock (Reg)",
                'render_ids' => json_encode(["lobbyArea2"]),
                'floor' => "0",
            ],
            [
                'name' => "Parkplatz",
                'description' => "Hinterer Parkplatz (Motorfurs)",
                'render_ids' => json_encode(["parkplatzArea1", "parkplatzArea2"]),
                'floor' => "0",
            ],
            [
                'name' => "Feuerplatz",
                'render_ids' => json_encode(["feuerArea"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'description' => "Volleyballfeld",
                'render_ids' => json_encode(["volleyArea"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'description' => "BBQ Bereich",
                'render_ids' => json_encode(["outdoorArea1"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'description' => "Vor dem Hotel",
                'render_ids' => json_encode(["outdoorArea2"]),
                'floor' => "0",
            ],
            [
                'name' => "Außenbereich",
                'description' => "Hinten auf der Wiese",
                'render_ids' => json_encode(["outdoorArea6"]),
                'floor' => "0",
            ],
            [
                'name' => "Pool",
                'render_ids' => json_encode(["poolArea"]),
                'floor' => "0",
            ],
            [
                'name' => "Restaurant Philharmonie",
                'render_ids' => json_encode(["brekkyArea1", "brekkyArea2", "brekkyArea3", "brekkyArea4"]),
                'floor' => "0",
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
            ],
            [
                'name' => "Rezeption",
                'render_ids' => json_encode(["counterArea"]),
                'floor' => "0",
            ],
            [
                'name' => "Kulisse",
                'render_ids' => json_encode([]),
                'floor' => "-2",
            ],
            [
                'name' => "Außenbereich",
                'description' => "Hinder der Bar",
                'render_ids' => json_encode(["outdoorArea3"]),
                'floor' => "0",
            ]
        ];

        foreach($locations AS $location) {
            SigLocation::insert($location);
        }
    }
}
