<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    use HasFactory;

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
        $client = new Client();
        $response = $client->post("https://api.lassie.online/v2.0", [
            'form_params' => [
                'apikey' => config('app.lassieApiKey'),
                'request' => 'lostandfounddb',
            ]
        ]);

        if($response->getStatusCode() < 400) {
            $json = json_decode($response->getBody()->getContents());

            // Dingo weiß leider nicht wie man HTTP Status Codes nutzt >.>
            if(!$json OR !is_object($json) OR isset($json->error))
                return;

            foreach($json->data AS $apiEntry) {
                LostFoundItem::updateOrCreate([
                    'lassie_id' => $apiEntry->id,
                    'image_url' => $apiEntry->image,
                    'thumb_url' => $apiEntry->thumb,
                    'title' => $apiEntry->title,
                    'description' => $apiEntry->description,
                    'status' => $apiEntry->status,
                    'lost_at' => $apiEntry->lost_timestamp,
                    'found_at' => $apiEntry->found_timestamp,
                    'returned_at' => $apiEntry->return_timestamp,
                ]);
            }
        }
    }
}
