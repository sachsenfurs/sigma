<?php

namespace Database\Seeders\RealData;

use App\Models\Info\Enums\ShowMode;
use App\Models\Info\Social;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class EASTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        (new RingbergSeeder())->run();

        @copy(Storage::disk("local")->path("") . "../../database/seeders/RealData/Resources/qr_tg.png", Storage::disk("local")->path("public/qr_tg.png"));
        @copy(Storage::disk("local")->path("") . "../../database/seeders/RealData/Resources/qr_tg_en.png", Storage::disk("local")->path("public/qr_tg_en.png"));
        @copy(Storage::disk("local")->path("") . "../../database/seeders/RealData/Resources/qr_discord.png", Storage::disk("local")->path("public/qr_discord.png"));
        @copy(Storage::disk("local")->path("") . "../../database/seeders/RealData/Resources/qr_insta.png", Storage::disk("local")->path("public/qr_insta.png"));
        @copy(Storage::disk("local")->path("") . "../../database/seeders/RealData/Resources/qr_sigma.png", Storage::disk("local")->path("public/qr_sigma.png"));
        @copy(Storage::disk("local")->path("") . "../../database/seeders/RealData/Resources/qr_twitter.png", Storage::disk("local")->path("public/qr_twitter.png"));

        Social::insert([
            [
                "description" => "EAST Info",
                "description_en" => "EAST Info (English)",
                "link_name" => "@eastinfo",
                "link" => "https://t.me/eastinfo",
                "link_en" => "https://t.me/eastinfo_en",
                "link_name_en" => "@eastinfo_en",
                "icon" => "telegram",
                "image" => "qr_tg.png",
                "image_en" => "qr_tg_en.png",
                "show_on" => json_encode([ShowMode::SIGNAGE, ShowMode::FOOTER_ICON]),
                'order' => 100,
            ],
            [
                "description" => "Sachsen Furs e.V.",
                "description_en" => "Sachsen Furs e.V.",
                "link_name" => "@sachsenfurs",
                "link" => "https://x.com/sachsenfurs",
                "link_name_en" => "@sachsenfurs",
                "link_en" => "https://x.com/sachsenfurs",
                "icon" => "twitter",
                "image" => "qr_twitter.png",
                "image_en" => "qr_twitter.png",
                "show_on" => json_encode([ShowMode::SIGNAGE, ShowMode::FOOTER_ICON]),
                'order' => 200,
            ],
            [
                "description" => "Discord",
                "description_en" => "Discord",
                "link_name" => "sachsenfurs.de/discord",
                "link" => "https://sachsenfurs.de/discord",
                "link_name_en" => "sachsenfurs.de/discord",
                "link_en" => "https://sachsenfurs.de/discord",
                "icon" => "discord",
                "image" => "qr_discord.png",
                "image_en" => "qr_discord.png",
                "show_on" => json_encode([ShowMode::SIGNAGE, ShowMode::FOOTER_ICON]),
                'order' => 300,
            ],
            [
                "description" => "Instagram",
                "description_en" => "Instagram",
                "link_name" => "@sachsenfurs",
                "link" => "https://www.instagram.com/sachsenfurs/",
                "link_name_en" => "@sachsenfurs",
                "link_en" => "https://www.instagram.com/sachsenfurs/",
                "icon" => "instagram",
                "image" => "qr_insta.png",
                "image_en" => "qr_insta.png",
                "show_on" => json_encode([ShowMode::SIGNAGE, ShowMode::FOOTER_ICON]),
                'order' => 300,
            ],
            [
                "description_" => "SIGMA - Die Webapp für die Convention!",
                "description_en" => "SIGMA - The webapp for our convention!",
                "link_name" => "sigma.sachsenfurs.de",
                "link" => "https://sigma.sachsenfurs.de",
                "link_name_en" => "https://sigma.sachsenfurs.de",
                "link_en" => "https://sigma.sachsenfurs.de",
                "icon" => "SIGMA",
                "image" => "qr_sigma.png",
                "image_en" => "qr_sigma.png",
                "show_on" => json_encode([ShowMode::SIGNAGE]),
                'order' => 400,
            ],
            [
                "description" => "EAST Website",
                "description_en" => "EAST Website",
                "link_name" => "EAST Website",
                "link" => "https://sachsenfurs.de/de/east",
                "link_name_en" => "EAST Website",
                "link_en" => "https://sachsenfurs.de/de/east",
                "icon" => "",
                "image" => "",
                "image_en" => "",
                "show_on" => json_encode([ShowMode::FOOTER_TEXT]),
                'order' => 10,
            ],
            [
                "description" => "Impressum",
                "description_en" => "Imprint",
                "link_name" => "Impressum",
                "link" => "https://sachsenfurs.de/de/verein/impressum/",
                "link_name_en" => "Imprint",
                "link_en" => "https://sachsenfurs.de/de/verein/impressum/",
                "icon" => "",
                "image" => "",
                "image_en" => "",
                "show_on" => json_encode([ShowMode::FOOTER_TEXT]),
                'order' => 15,
            ],
            [
                "description" => "Datenschutzerklärung",
                "description_en" => "Privacy Policy",
                "link_name" => "Datenschutzerklärung",
                "link" => "https://sachsenfurs.de/de/datenschutz/",
                "link_name_en" => "Privacy Policy",
                "link_en" => "https://sachsenfurs.de/de/datenschutz/",
                "icon" => "",
                "image" => "",
                "image_en" => "",
                "show_on" => json_encode([ShowMode::FOOTER_TEXT]),
                'order' => 20,
            ]
        ]);
    }
}
