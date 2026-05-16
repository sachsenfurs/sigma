<?php

namespace App\Integrations\Lassie;

use Illuminate\Http\Client\PendingRequest;

class LassieClient
{
    public function __construct(
        private readonly PendingRequest $http,
        private readonly string $endpoint,
        private readonly string $apiKey,
    ) {
    }

    public function lostAndFoundDatabase(): ?object {
        $response = $this->http->post($this->endpoint, [
            'apikey' => $this->apiKey,
            'request' => 'lostandfounddb',
        ]);

        if($response->failed()) {
            return null;
        }

        $json = json_decode($response->body());

        if(!$json || !is_object($json) || isset($json->error)) {
            return null;
        }

        return $json;
    }
}
