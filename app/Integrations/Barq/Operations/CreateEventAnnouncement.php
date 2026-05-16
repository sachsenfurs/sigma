<?php

namespace App\Integrations\Barq\Operations;

use App\Integrations\Barq\GraphQLClient;
use UnexpectedValueException;

class CreateEventAnnouncement extends Operation
{
    public function __construct(
        GraphQLClient $client,
        private readonly string $uuid,
        private readonly string $title,
        private readonly string $body,
    ) {
        parent::__construct($client);
    }

    public function query(): string {
        return <<<'GRAPHQL'
            mutation CreateAnnouncement(
                $uuid: String!
                $input: EventAnnouncementInput!
            ) {
                eventAnnouncementCreate(
                    uuid: $uuid
                    input: $input
                )
                {
                    id
                }
            }
        GRAPHQL;
    }

    public function variables(): array {
        return [
            'uuid' => $this->uuid,
            'input' => [
                'title' => $this->title,
                'body' => $this->body,
                'attendeeOnly' => false,
            ]
        ];
    }

    protected function transform(array $data): string {
        $id = $this->data($data, 'eventAnnouncementCreate.id');

        if(!is_string($id) && !is_int($id)) {
            throw new UnexpectedValueException('eventAnnouncementCreate.id is missing in response.');
        }

        return (string) $id;
    }
}
