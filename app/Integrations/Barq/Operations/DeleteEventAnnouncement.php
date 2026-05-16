<?php

namespace App\Integrations\Barq\Operations;

use App\Integrations\Barq\GraphQLClient;
use UnexpectedValueException;

class DeleteEventAnnouncement extends Operation
{
    public function __construct(
        GraphQLClient $client,
        private readonly string $id
    ) {
        parent::__construct($client);
    }

    public function query(): string {
        return <<<'GRAPHQL'
            mutation DeleteAnnouncement(
                $id: ID!
            ) {
                eventAnnouncementDelete(
                    id: $id
                )
            }
        GRAPHQL;
    }

    public function variables(): array {
        return [
            'id' => $this->id,
        ];
    }
}
