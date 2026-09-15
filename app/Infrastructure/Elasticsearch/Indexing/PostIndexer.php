<?php

namespace App\Infrastructure\Elasticsearch\Indexing;

use App\Infrastructure\Elasticsearch\Documents\PostDocument;
use App\Infrastructure\Elasticsearch\Indices\PostIndex;
use Elastic\Elasticsearch\Client;

class PostIndexer
{
    public function __construct(
        private Client $client
    ) {}

    public function index(iterable $posts): void
    {
        $params = [
            'body' => [],
        ];

        foreach ($posts as $post) {
            $params['body'][] = [
                'index' => [
                    '_index' => PostIndex::NAME,
                    '_id' => $post->id,
                ],
            ];

            $params['body'][] = PostDocument::fromDTO($post);

            if (count($params['body']) >= 1000) {
                $this->client->bulk($params);
                $params = ['body' => []];
            }
        }

        if (! empty($params['body'])) {
            $this->client->bulk($params);
        }
    }
}
