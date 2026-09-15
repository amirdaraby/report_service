<?php

namespace App\Providers;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class ElasticServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            return ClientBuilder::create()
                ->setHosts([
                    config('services.elasticsearch.host').':'.config('services.elasticsearch.port'),
                ])
                ->setApiKey(config('services.elasticsearch.api_key'))
                ->build();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
