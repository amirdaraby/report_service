<?php

namespace App\Infrastructure\Elasticsearch;

use Elastic\Elasticsearch\Client;

interface ClientBuilderInterface
{
    public function default(): Client;

    public function connection(string $name): Client;
}
