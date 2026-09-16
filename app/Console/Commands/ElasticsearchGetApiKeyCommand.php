<?php

namespace App\Console\Commands;

use App\Infrastructure\Elasticsearch\IndexManager;
use Illuminate\Console\Command;

final class ElasticsearchGetApiKeyCommand extends Command
{
    protected $signature = 'elasticsearch:get-api-key {--overwrite : Overwrite existing API key}';

    protected $description = 'Generate an Elasticsearch API key and save it to .env';

    public function __construct(
        private IndexManager $indexManager,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $currentKey = config('elastic.connections.default.api_key');

        if ($currentKey && ! $this->option('overwrite')) {
            $this->info('ELASTICSEARCH_API_KEY already exists, skipping generation. Use --overwrite to overwrite.');

            return self::SUCCESS;
        }

        try {
            $encoded = $this->indexManager->createApiKey('report_service_laravel');

            $this->updateEnv($encoded);

            $this->info('ELASTICSEARCH_API_KEY written to .env');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to create Elasticsearch API key: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    private function updateEnv(string $key): void
    {
        $envPath = base_path('.env');
        $contents = file_get_contents($envPath);

        if (preg_match('/^ELASTICSEARCH_API_KEY=.*/m', $contents)) {
            $contents = preg_replace('/^ELASTICSEARCH_API_KEY=.*/m', "ELASTICSEARCH_API_KEY={$key}", $contents);
        } else {
            $contents .= "\nELASTICSEARCH_API_KEY={$key}\n";
        }

        file_put_contents($envPath, $contents);
    }
}
