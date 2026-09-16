<?php

namespace App\Providers;

use App\Application\Contracts\Repositories\PostRepository;
use App\Application\Contracts\Repositories\ReportRepository;
use App\Application\Contracts\Repositories\UserRepository;
use App\Application\Contracts\Delivery\ReportDelivery;
use App\Application\Contracts\Export\ReportExporter;
use App\Infrastructure\Eloquent\Repositories\EloquentReportRepository;
use App\Infrastructure\Eloquent\Repositories\EloquentUserRepository;
use App\Infrastructure\Elasticsearch\Repositories\ElasticsearchPostRepository;
use App\Infrastructure\Exports\ExcelReportExporter;
use App\Infrastructure\Mail\MailReportDelivery;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(ReportRepository::class, EloquentReportRepository::class);
        $this->app->bind(PostRepository::class, ElasticsearchPostRepository::class);

        $this->app->bind(ReportExporter::class, ExcelReportExporter::class);
        $this->app->bind(ReportDelivery::class, MailReportDelivery::class);
    }
}
