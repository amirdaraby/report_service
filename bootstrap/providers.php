<?php

use App\Providers\AppServiceProvider;
use App\Providers\ElasticServiceProvider;
use App\Providers\PaginatorServiceProvider;

return [
    AppServiceProvider::class,
    ElasticServiceProvider::class,
    PaginatorServiceProvider::class,
];
