<?php

namespace App\Integrations\Barq;

use App\Integrations\Barq\Exceptions\GraphQLRequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;

class GraphQLClient
{
    public function __construct(
        private readonly PendingRequest $http,
        private readonly string $endpoint,
    ) {
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function execute(string $query, array $variables = []): array {
        $payload = [
            'query' => $query,
            'variables' => $variables,
        ];

        $response = $this->http
            ->post($this->endpoint, $payload)
            ->throw();

        $responseData = $response->json();

        if(!is_array($responseData)) {
            throw new GraphQLRequestException(
                'Invalid GraphQL response.',
                responseBody: $response->body(),
            );
        }

        $errors = $responseData['errors'] ?? [];
        if(is_array($errors) && !empty($errors)) {
            throw GraphQLRequestException::fromErrors($errors, $response->body());
        }

        $data = $responseData['data'] ?? [];

        if($data === null) {
            return [];
        }

        if(!is_array($data)) {
            throw new GraphQLRequestException(
                'Invalid GraphQL data payload.',
                responseBody: $response->body(),
            );
        }

        return $data;
    }
}
