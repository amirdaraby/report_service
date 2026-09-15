<?php

namespace App\Infrastructure\Elasticsearch\Indices;

final class PostIndex
{
    public const NAME = 'posts';

    public static function definition(): array
    {
        return [
            'settings' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 0,
            ],
            'mappings' => [
                'dynamic' => false,
                '_source' => [
                    'enabled' => true,
                ],
                'properties' => [
                    'id' => [
                        'type' => 'keyword',
                    ],

                    'title' => [
                        'type' => 'text',
                    ],

                    'lead' => [
                        'type' => 'text',
                    ],

                    'content' => [
                        'type' => 'text',
                    ],

                    'published_at' => [
                        'type' => 'date',
                    ],

                    'news_agency_name' => [
                        'type' => 'keyword',
                    ],

                    'news_agency_id' => [
                        'type' => 'keyword',
                    ],

                    'categories' => [
                        'type' => 'keyword',
                    ],

                    'tags' => [
                        'type' => 'keyword',
                    ],

                    'lf_lang' => [
                        'type' => 'keyword',
                    ],

                    'url' => [
                        'type' => 'keyword',
                        'index' => false,
                    ],

                    'main_images' => [
                        'type' => 'keyword',
                        'index' => false,
                    ],
                ],
            ],
        ];
    }
}
