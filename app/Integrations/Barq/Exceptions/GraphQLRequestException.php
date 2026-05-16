<?php

namespace App\Integrations\Barq\Exceptions;

use RuntimeException;

class GraphQLRequestException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly array $errors = [],
        private readonly ?string $responseBody = null,
    ) {
        parent::__construct($message);
    }

    public static function fromErrors(array $errors, ?string $responseBody = null): self
    {
        $messages = [];

        foreach($errors as $error) {
            if(is_array($error) && isset($error['message']) && is_string($error['message'])) {
                $messages[] = $error['message'];
            }
        }

        return new self(
            empty($messages) ? 'GraphQL request failed.' : implode('; ', $messages),
            $errors,
            $responseBody,
        );
    }

    public function errors(): array {
        return $this->errors;
    }

    public function responseBody(): ?string {
        return $this->responseBody;
    }
}
