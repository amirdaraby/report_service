<?php

namespace App\Console\Commands;

use App\Application\DTO\PostData;
use App\Infrastructure\Elasticsearch\Indexing\PostIndexer;
use Illuminate\Console\Command;

final class ImportPostsCommand extends Command
{
    protected $signature = 'elasticsearch:import-posts {path : Path to the JSON seed file}';

    protected $description = 'Import posts from JSON file into Elasticsearch';

    public function __construct(
        private readonly PostIndexer $postIndexer
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $path = $this->argument('path');

        if (! file_exists($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        $json = file_get_contents($path);
        $records = json_decode($json, true);

        if (! is_array($records)) {
            $this->error('Invalid JSON format');

            return self::FAILURE;
        }

        $posts = array_map(
            fn (array $record) => new PostData(
                id: $record['id'] ?? '',
                title: $record['title'] ?? '',
                lead: $record['lead'] ?? null,
                content: $record['content'] ?? '',
                publishedAt: $record['published_at'] ?? '',
                newsAgencyName: $record['news_agency_name'] ?? null,
                url: $record['url'] ?? null,
                categories: $record['categories'] ?? [],
                tags: $record['tags'] ?? [],
                mainImages: $record['main_images'] ?? [],
                language: $record['lf_lang'] ?? null,
                newsAgencyId: $record['news_agency_id'] ?? null,
            ),
            $records
        );

        $this->postIndexer->index($posts);

        $this->info('Imported '.count($posts).' posts into Elasticsearch.');

        return self::SUCCESS;
    }
}
