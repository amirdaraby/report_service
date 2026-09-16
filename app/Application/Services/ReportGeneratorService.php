<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\PostRepository;
use App\Application\Contracts\Repositories\ReportRepository;
use App\Application\Contracts\Delivery\ReportDelivery;
use App\Application\Contracts\Export\ReportExporter;
use App\Enums\Frequency;
use App\Models\Report;
use Carbon\Carbon;

final class ReportGeneratorService
{
    public function __construct(
        private ReportRepository $reportRepository,
        private PostRepository   $postRepository,
        private ReportExporter   $reportExporter,
        private ReportDelivery   $reportDelivery,
    )
    {
    }

    public function generate(int $reportId, Carbon $scheduledAt): void
    {
        $report = $this->reportRepository->findOrFailWithUser($reportId);

        $data = $this->postRepository->dailyHistogram(
            $report->keywords,
            $this->from($report, $scheduledAt),
            $scheduledAt,
        );

        $filePath = $this->reportExporter->export($report, $data);

        $this->reportDelivery->send($report, $filePath);

        $this->reportRepository->update($report->id, [
            'last_run_at' => Carbon::now(),
        ]);
    }

    private function from(Report $report, Carbon $scheduledAt): Carbon
    {
        return match ($report->frequency) {
            Frequency::DAILY => $scheduledAt->copy()->subDay(),
            Frequency::WEEKLY => $scheduledAt->copy()->subWeek(),
        };
    }
}
