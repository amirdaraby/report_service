<?php

namespace App\Infrastructure\Elasticsearch;

use Elastic\Elasticsearch\Client;

final readonly class IndexManager
{
    public function __construct(
        private ClientBuilderInterface $clientBuilder,
    )
    {
    }

    public function exists(string $index): bool
    {
        return $this->client()
            ->indices()
            ->exists([
                'index' => $index,
            ])
            ->asBool();
    }

    public function create(
        string $index,
        array  $definition
    ): void
    {
        $this->client()
            ->indices()
            ->create([
                'index' => $index,
                'body' => $definition,
            ]);
    }

    public function createApiKey(string $name): string
    {
        $response = $this->clientBuilder
            ->connection('basic')
            ->security()
            ->createApiKey([
                'body' => [
                    'name' => $name,
                ],
            ]);

        $encoded = $response->asArray()['encoded'] ?? null;

        if (!$encoded) {
            throw new \RuntimeException('Failed to create Elasticsearch API key.');
        }

        return $encoded;
    }

    private function client(): Client
    {
        return $this->clientBuilder->default();
    }
}
