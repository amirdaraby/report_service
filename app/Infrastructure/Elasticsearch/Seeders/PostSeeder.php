<?php

namespace App\Infrastructure\Elasticsearch\Seeders;

use App\Infrastructure\Elasticsearch\Indexing\PostIndexer;

final class PostSeeder
{
    public function __construct(
        private readonly PostIndexer $postIndexer,
        private readonly PostDataFactory $factory,
    ) {}

    public function generate(int $count, int $days): int
    {
        $posts = $this->factory->make($count, $days);

        $this->postIndexer->index($posts);

        return $count;
    }
}
