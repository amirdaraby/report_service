<?php

namespace App\Jobs;

use App\Application\Services\ReportGeneratorService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(
        public int $reportId,
        public Carbon $scheduledAt,
    ) {
        $this->onQueue('reports');
    }

    public function handle(ReportGeneratorService $reportGenerator): void
    {
        $reportGenerator->generate($this->reportId, $this->scheduledAt);
    }
}
