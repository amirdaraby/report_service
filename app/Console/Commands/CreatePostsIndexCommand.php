<?php

namespace App\Console\Commands;

use App\Infrastructure\Elasticsearch\IndexManager;
use App\Infrastructure\Elasticsearch\Indices\PostIndex;
use Illuminate\Console\Command;

final class CreatePostsIndexCommand extends Command
{
    protected $signature = 'elasticsearch:create-posts-index';

    protected $description = 'Create the Elasticsearch posts index with mappings';

    public function __construct(
        private IndexManager $indexManager,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if ($this->indexManager->exists(PostIndex::NAME)) {
            $this->info('Posts index already exists.');

            return self::INVALID;
        }

        $this->indexManager->create(PostIndex::NAME, PostIndex::definition());
        $this->info('Posts index created successfully.');

        return self::SUCCESS;
    }
}
