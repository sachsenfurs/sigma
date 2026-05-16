<?php

namespace App\Integrations\Barq\Operations;

use App\Integrations\Barq\Contracts\GraphQLOperation;
use App\Integrations\Barq\GraphQLClient;

abstract class Operation implements GraphQLOperation
{
    public function __construct(
        protected readonly GraphQLClient $client,
    ) {
    }

    public function execute(): mixed {
        return $this->transform(
            $this->client->execute(
                $this->query(),
                $this->variables()
            )
        );
    }

    public function variables(): array {
        return [];
    }

    protected function transform(array $data): mixed {
        return $data;
    }

    protected function data(array $data, string $path, mixed $default = null): mixed {
        return data_get($data, $path, $default);
    }
}
