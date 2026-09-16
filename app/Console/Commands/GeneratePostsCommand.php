<?php

namespace App\Console\Commands;

use App\Infrastructure\Elasticsearch\Seeders\PostSeeder;
use Illuminate\Console\Command;

final class GeneratePostsCommand extends Command
{
    protected $signature = 'elasticsearch:generate-posts
        {--count=1000 : Number of posts to generate}
        {--days=30 : Spread posts across this many days}';

    protected $description = 'Generate fake posts in Elasticsearch for benchmarks';

    public function __construct(
        private readonly PostSeeder $postSeeder,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $count = (int) $this->option('count');
        $days = (int) $this->option('days');

        $this->info("Generating {$count} posts across {$days} days...");

        $generated = $this->postSeeder->generate($count, $days);

        $this->info("Generated {$generated} posts into Elasticsearch.");

        return self::SUCCESS;
    }
}
