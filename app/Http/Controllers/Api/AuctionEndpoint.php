<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuctionEndpoint extends Controller
{
    public function index() {
        return [
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
            ]
        ];

    }
}
