<?php

namespace App\Providers;

use App\Application\Contracts\Repositories\PostRepository;
use App\Application\Contracts\Repositories\ReportRepository;
use App\Application\Contracts\Repositories\UserRepository;
use App\Infrastructure\Eloquent\Repositories\EloquentReportRepository;
use App\Infrastructure\Eloquent\Repositories\EloquentUserRepository;
use App\Infrastructure\Elasticsearch\Repositories\ElasticsearchPostRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(ReportRepository::class, EloquentReportRepository::class);

        $this->app->bind(PostRepository::class, ElasticsearchPostRepository::class);
    }

    public function boot(): void
    {
    }
}
