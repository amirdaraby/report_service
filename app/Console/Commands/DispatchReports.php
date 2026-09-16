<?php

namespace App\Console\Commands;

use App\Application\Services\ReportSchedulerService;
use Illuminate\Console\Command;

final class DispatchReports extends Command
{
    protected $signature = 'reports:dispatch';

    protected $description = 'Dispatch due periodic reports';

    public function handle(ReportSchedulerService $scheduler): int
    {
        $scheduler->dispatchDueReports();

        return self::SUCCESS;
    }
}
