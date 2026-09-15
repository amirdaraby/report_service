<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Application\Contracts\Repositories\ReportRepository;
use App\Models\Report;
use Illuminate\Database\Eloquent\Collection;

class EloquentReportRepository implements ReportRepository
{
    public function __construct(
        private Report $model
    ) {}

    public function listByUserId($userId, array $columns = ['*']): Collection
    {
        return $this->model->newQuery()
            ->where('user_id', $userId)
            ->get($columns);
    }

    public function create($data): Report
    {
        return $this->model->newQuery()->create($data);
    }
}
