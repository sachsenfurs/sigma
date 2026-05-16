<?php

namespace App\Integrations\Barq\Contracts;

interface GraphQLOperation
{
    public function query(): string;

    public function variables(): array;

}
