<?php

namespace App\Infrastructure\Elasticsearch;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

final class ConnectionBuilder implements ClientBuilderInterface
{
    private array $clients = [];

    public function default(): Client
    {
        $name = config('elastic.default');

        return $this->connection($name);
    }

    public function connection(string $name): Client
    {
        if (isset($this->clients[$name])) {
            return $this->clients[$name];
        }

        $config = config("elastic.connections.{$name}");

        if (! $config) {
            throw new \InvalidArgumentException("Elasticsearch connection [{$name}] not found.");
        }

        $builder = ClientBuilder::create()
            ->setHosts(["{$config['host']}:{$config['port']}"]);

        if ($config['auth'] == 'api_key') {
            $builder->setApiKey($config['api_key']);
        } elseif ($config['auth'] == 'basic') {
            $builder->setBasicAuthentication($config['username'], $config['password']);
        }

        $this->clients[$name] = $builder->build();

        return $this->clients[$name];
    }
}
