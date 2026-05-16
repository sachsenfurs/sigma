<?php

namespace App\Integrations\Lassie\Operations;

use App\Integrations\Lassie\LassieClient;
use App\Models\LostFoundItem;

class SyncLostFoundItems
{
    public function __construct(
        private readonly LassieClient $client,
    ) {
    }

    public function execute(): void {
        $json = $this->client->lostAndFoundDatabase();

        if($json === null || !isset($json->data) || !is_iterable($json->data)) {
            return;
        }

        $apiEntries = collect($json->data);

        $delete = LostFoundItem::all()
            ->pluck("lassie_id")
            ->diff($apiEntries->pluck("id"));

        LostFoundItem::whereIn("lassie_id", $delete)->delete();

        foreach($apiEntries as $apiEntry) {
            LostFoundItem::updateOrCreate(
                [
                    'lassie_id' => $apiEntry->id
                ],
                [
                    'image_url' => $apiEntry->image,
                    'thumb_url' => $apiEntry->thumb,
                    'title' => mb_convert_encoding($apiEntry->title, "iso-8859-1", "utf8"),
                    'description' => mb_convert_encoding($apiEntry->description, "iso-8859-1", "utf8"),
                    'status' => $apiEntry->status,
                    'lost_at' => $apiEntry->lost_timestamp,
                    'found_at' => $apiEntry->found_timestamp,
                    'returned_at' => $apiEntry->return_timestamp,
                ]
            );
        }
    }
}
