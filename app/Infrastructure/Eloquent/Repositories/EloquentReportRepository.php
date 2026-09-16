<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Application\Contracts\Repositories\ReportRepository;
use App\Enums\Status;
use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class EloquentReportRepository implements ReportRepository
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
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function getDueReports(int $limit): Collection
    {
        return $this->model->query()
            ->where('status', Status::ACTIVE)
            ->where('next_run_at', '<=', now())
            ->whereNull('deleted_at')
            ->orderBy('next_run_at')
            ->limit($limit)
            ->lock('FOR UPDATE SKIP LOCKED')
            ->get();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->query()->where('id', $id)->update($data);
    }

    public function findOrFailWithUser(int $id): Report
    {
        return $this->model->query()->with('user')->findOrFail($id);
    }
}
