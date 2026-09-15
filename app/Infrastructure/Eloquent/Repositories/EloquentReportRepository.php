<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Application\Contracts\Repositories\ReportRepository;
use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentReportRepository implements ReportRepository
{
    public function __construct(
        private Report $model
    ) {}

    public function create(array $data): Report
    {
        return $this->model->query()->create($data);
    }

    public function listByUserIdPaginated(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
