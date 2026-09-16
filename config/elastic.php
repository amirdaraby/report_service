<?php

return [
    'default' => env('ELASTIC_CONNECTION', 'default'),

    'connections' => [
        'default' => [
            'host' => env('ELASTICSEARCH_HOST', 'http://elasticsearch'),
            'port' => env('ELASTICSEARCH_PORT', 9200),
            'auth' => 'api_key',
            'api_key' => env('ELASTICSEARCH_API_KEY'),
        ],

        'basic' => [
            'host' => env('ELASTICSEARCH_HOST', 'http://elasticsearch'),
            'port' => env('ELASTICSEARCH_PORT', 9200),
            'auth' => 'basic',
            'username' => env('ELASTICSEARCH_USERNAME'),
            'password' => env('ELASTICSEARCH_PASSWORD'),
        ],
    ],
];
