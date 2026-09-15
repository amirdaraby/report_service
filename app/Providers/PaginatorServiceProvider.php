<?php

namespace App\Providers;

use App\Paginator\CustomLengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class PaginatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LengthAwarePaginator::class, CustomLengthAwarePaginator::class);
    }
}
