<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\ReportRepository;
use App\Enums\Frequency;
use App\Enums\Status;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ReportService
{
    public function __construct(
        private ReportRepository $reportRepository
    )
    {
    }

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

    private function nextRun(Frequency $frequency, Carbon $from): Carbon
    {
        return match ($frequency) {
            Frequency::DAILY => $from->copy()->addDay(),
            Frequency::WEEKLY => $from->copy()->addWeek(),
        };
    }
}
