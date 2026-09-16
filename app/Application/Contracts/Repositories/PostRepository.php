<?php

namespace App\Application\Contracts\Repositories;

use Carbon\Carbon;

interface PostRepository
{
    public function dailyHistogram(array $keywords, Carbon $from, Carbon $to): array;
}
