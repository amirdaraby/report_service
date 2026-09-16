<?php

namespace App\Application\Contracts\Repositories;

use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ReportRepository
{
    public function create(array $data): Report;

    public function listByUserIdPaginated(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function getDueReports(int $limit): Collection;

    public function findOrFailWithUser(int $id): Report;

    public function update(int $id, array $data): bool;
}
