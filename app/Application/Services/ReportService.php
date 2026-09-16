<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\ReportRepository;
use App\Application\Services\Traits\SchedulesReports;
use App\Enums\Frequency;
use App\Enums\Status;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ReportService
{
    use SchedulesReports;

    public function __construct(
        private ReportRepository $reportRepository,
    ) {}

    public function create(int $userId, string $name, Frequency $frequency, array $keywords): Report
    {
        return $this->reportRepository->create([
            'user_id' => $userId,
            'name' => $name,
            'frequency' => $frequency,
            'status' => Status::ACTIVE,
            'keywords' => $keywords,
            'last_run_at' => null,
            'next_run_at' => $this->nextRun($frequency, Carbon::now()),
        ]);
    }

    public function listByUserIdPaginated(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->reportRepository->listByUserIdPaginated($userId, $perPage);
    }
}
