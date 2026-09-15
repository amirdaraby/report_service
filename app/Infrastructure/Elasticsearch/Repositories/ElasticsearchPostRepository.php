<?php

namespace App\Infrastructure\Elasticsearch\Repositories;

use App\Application\Contracts\Repositories\PostRepository;
use Elastic\Elasticsearch\Client;

class ElasticsearchPostRepository implements PostRepository
{
    public function __construct(
        private Client $client
    ) {}

    // TODO: fix the name PRIORITY 1
    public function searchByKeywordAndDateBetweenDaysetc() {}
}
