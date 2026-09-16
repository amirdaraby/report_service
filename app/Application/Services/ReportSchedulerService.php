<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\ReportRepository;
use App\Application\Services\Traits\SchedulesReports;
use App\Jobs\GenerateReportJob;
use Illuminate\Support\Facades\DB;

final class ReportSchedulerService
{
    use SchedulesReports;

    public function __construct(
        private ReportRepository $reportRepository,
    ) {}

    public function dispatchDueReports(int $limit = 100): void
    {
        DB::transaction(function () use ($limit) {
            $reports = $this->reportRepository->getDueReports($limit);

            foreach ($reports as $report) {
                $scheduledAt = $report->next_run_at;
                $this->reportRepository->update($report->id, [
                    'next_run_at' => $this->nextRun($report->frequency, $report->next_run_at),
                ]);

                GenerateReportJob::dispatch($report->id, $scheduledAt)
                    ->afterCommit();
            }
        });
    }
}
