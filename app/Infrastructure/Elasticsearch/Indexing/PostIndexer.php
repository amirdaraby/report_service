<?php

namespace App\Infrastructure\Elasticsearch\Indexing;

use App\Infrastructure\Elasticsearch\ClientBuilderInterface;
use App\Infrastructure\Elasticsearch\Documents\PostDocument;
use App\Infrastructure\Elasticsearch\Indices\PostIndex;

final class PostIndexer
{
    public function __construct(
        private ClientBuilderInterface $clientBuilder,
    ) {}

    public function index(iterable $posts): void
    {
        $client = $this->clientBuilder->default();

        $params = [
            'body' => [],
        ];

        foreach ($posts as $post) {
            $action = ['index' => ['_index' => PostIndex::NAME]];

            if ($post->id) {
                $action['index']['_id'] = $post->id;
            }

            $params['body'][] = $action;

            $params['body'][] = PostDocument::fromDTO($post);

            if (count($params['body']) >= 1000) {
                $client->bulk($params);
                $params = ['body' => []];
            }
        }

        if (! empty($params['body'])) {
            $client->bulk($params);
        }
    }
}
