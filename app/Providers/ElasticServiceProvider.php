<?php

namespace App\Providers;

use App\Infrastructure\Elasticsearch\ClientBuilderInterface;
use App\Infrastructure\Elasticsearch\ConnectionBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ClientBuilderInterface::class, ConnectionBuilder::class);
    }
}
