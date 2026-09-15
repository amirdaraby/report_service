<?php

namespace App\Infrastructure\Elasticsearch;

use Elastic\Elasticsearch\Client;

final readonly class IndexManager
{
    public function __construct(
        private Client $client,
    ) {}

    // TODO: error handling ?
    // TODO: should we just ignore or throw error if index already exists ?
    public function exists(string $index): bool
    {
        return $this->client
            ->indices()
            ->exists([
                'index' => $index,
            ])
            ->asBool();
    }

    public function create(
        string $index,
        array $definition
    ): void {
        $this->client
            ->indices()
            ->create([
                'index' => $index,
                'body' => $definition,
            ]);
    }
}
