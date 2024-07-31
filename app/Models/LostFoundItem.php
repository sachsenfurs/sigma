<?php

namespace App\Models;

use App\Settings\AppSettings;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'lost_at' => "datetime",
        'found_at' => "datetime",
        'returned_at' => "datetime",
    ];

    public function scopeFound(Builder $query): Builder {
        return $query->where("status", "F");
    }

    public function scopeLost(Builder $query): Builder {
        return $query->where("status", "L");
    }

    public function scopeReturned(Builder $query): Builder {
        return $query->where("status", "R");
    }

    public static function syncFromApi() {
        // https://wiki.furcom.org/bin/view/L.A.S.S.I.E./System%20Information/API%20V2.0/
        $client = new Client();
        $response = $client->post("https://api.lassie.online/v2.0", [
            'form_params' => [
                'apikey' => app(AppSettings::class)->lassie_api_key,
                'request' => 'lostandfounddb',
            ]
        ]);

        if($response->getStatusCode() < 400) {
            $json = json_decode($response->getBody()->getContents());

            // Dingo weiß leider nicht wie man HTTP Status Codes nutzt >.>
            if(!$json OR !is_object($json) OR isset($json->error))
                return;

            // remove old entries
            $delete = LostFoundItem::all()->pluck("lassie_id")->diff(collect($json->data)->pluck("id"));
            LostFoundItem::whereIn("lassie_id", $delete)->delete();

            foreach($json->data AS $apiEntry) {
                LostFoundItem::updateOrCreate(
                    [
                        'lassie_id' => $apiEntry->id
                    ],
                    [
                        'image_url' => $apiEntry->image,
                        'thumb_url' => $apiEntry->thumb,
                        'title' => $apiEntry->title,
                        'description' => $apiEntry->description,
                        'status' => $apiEntry->status,
                        'lost_at' => $apiEntry->lost_timestamp,
                        'found_at' => $apiEntry->found_timestamp,
                        'returned_at' => $apiEntry->return_timestamp,
                    ]
                );
            }
        }
    }
}
