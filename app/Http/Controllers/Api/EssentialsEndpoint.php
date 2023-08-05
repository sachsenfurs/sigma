<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EssentialsEndpoint extends Controller
{
    public function index() {
        return [
            [
                "name" => "Toiletten",
                "name_en" => "Toilets",
                "location_ids" => [
                    "toilet3Area"
                ],
                "description" => "Toiletten",
                "description_en" => "Toilets"
            ],
            [
                "name" => "Toiletten",
                "name_en" => "Toilets",
                "location_ids" => [
                    "toilet1Area",
                    "toilet2Area",
                    "babyArea",
                    "potatoArea"
                ],
                "description" => "Toiletten, Toilette für körperlich beeinträchtigte Personen, Wickelraum",
                "description_en" => "Toilets, Toilet for handicapped people, Baby-Care Room"
            ],
            [
                "name" => "Rezeption",
                "name_en" => "Reception",
                "location_ids" => [
                ],
                "description" => "Bei Fragen und Anliegen zum Hotel und eurem Zimmer bekommst du hier Hilfe.",
                "description_en" => "If you have any questions or concerns about the hotel and your room, you can get help here."
            ],
            [
                "name" => "Pool & Sauna",
                "name_en" => "Pool & Sauna",
                "location_ids" => [
                    "poolArea"
                ],
                "description" => "Das kühle Nass steht dir täglich von 9 bis 24 Uhr zur Verfügung, für unsere Hotelgäste ist der Pool kostenfrei.<br>Zwischen 20 und 24 Uhr heizen wir mit Licht und Musik euch Wasserviechern ordentlich ein!<br>Die Benutzung des Pools mit Inflas ist zwischen 14 und 17 Uhr gestattet,",
                "description_en" => "The pool is available daily from 9am to midnight, for our hotel guests the pool is free of charge.<br>Between 8pm and midnight there will be music!<br>Inflas are permitted from 2pm to 5pm."
            ],
            [
                "name" => "Fursuit Lounge",
                "name_en" => "Fursuit Lounge",
                "location_ids" => [
                    "sachsenArea"
                ],
                "description" => "Die Fursuitlounge steht dir jeden Tag von 10:30 bis 1:30 Uhr zur Verfügung.<br>Hier kannst du dich erfrischen und deinen Suit trocknen.<br>Zutritt nur für Fursuiter. Ein Helfer ist gestattet.",
                "description_en" => "The Fursuit lounge is available daily from 10:30am to 1:30am.<br>You can refresh yourself and dry your fursuit here.<br>Access for fursuiters and one assistant per fursuiter only."
            ],
            [
                "name" => "Headless Area",
                "name_en" => "Headless Area",
                "location_ids" => [
                    "mainstageArea1"
                ],
                "description" => "Während Mainstage-Events hast du hier einen geschützten Bereich um dich zu erfrischen und deinen Head abzusetzen.",
                "description_en" => "During Mainstage events you can use this area to refresh yourself and take off your head."
            ],
            [
                "name" => "Frühstück",
                "name_en" => "Breakfast",
                "location_ids" => [
                    "brekkyArea1",
                    "brekkyArea2",
                    "brekkyArea3",
                    "brekkyArea4"
                ],
                "description" => "Täglich von 8:00Uhr bis 12:00Uhr",
                "description_en" => "Eveyday from 8am to 12pm"
            ]
        ];

    }
}
