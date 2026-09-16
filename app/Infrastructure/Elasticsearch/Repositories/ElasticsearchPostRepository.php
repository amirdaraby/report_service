<?php

namespace App\Infrastructure\Elasticsearch\Repositories;

use App\Application\Contracts\Repositories\PostRepository;
use App\Infrastructure\Elasticsearch\ClientBuilderInterface;
use App\Infrastructure\Elasticsearch\Indices\PostIndex;
use Carbon\Carbon;

final class ElasticsearchPostRepository implements PostRepository
{
    public function __construct(
        private ClientBuilderInterface $client,
    )
    {
    }

    public function dailyHistogram(
        array $keywords,
        Carbon $from,
        Carbon $to
    ): array {
        $response = $this->client->default()->search([
            'index' => PostIndex::NAME,
            'filter_path' => ['aggregations'],
            'body' => [
                'size' => 0,

                'query' => [
                    'bool' => [
                        'must' => $this->buildKeywordQueries($keywords),

                        'filter' => [
                            [
                                'range' => [
                                    'published_at' => [
                                        'gte' => $from->toIso8601String(),
                                        'lt' => $to->toIso8601String(),
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                'aggs' => [
                    'posts_per_day' => [
                        'date_histogram' => [
                            'field' => 'published_at',
                            'calendar_interval' => 'day',
                            'time_zone' => 'UTC',
                            'min_doc_count' => 0,
                            'extended_bounds' => [
                                'min' => $from->toIso8601String(),
                                'max' => $to->copy()->subSecond()->toIso8601String(),
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        return collect($response['aggregations']['posts_per_day']['buckets'])
            ->map(fn ($bucket) => [
                'date' => $bucket['key_as_string'],
                'count' => $bucket['doc_count'],
            ])
            ->all();
    }

    private function buildKeywordQueries(array $keywords): array
    {
        return array_map(
            fn (string $keyword) => [
                'multi_match' => [
                    'query' => $keyword,
                    'fields' => ['title', 'lead', 'content'],
                ],
            ],
            $keywords
        );
    }
}
