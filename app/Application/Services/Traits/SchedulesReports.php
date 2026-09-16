<?php

namespace App\Application\Services\Traits;

use App\Enums\Frequency;
use Carbon\Carbon;

trait SchedulesReports
{
    private function nextRun(Frequency $frequency, Carbon $from): Carbon
    {
        return match ($frequency) {
            Frequency::DAILY => $from->copy()->addDay()->startOfDay(),
            Frequency::WEEKLY => $from->copy()->addWeek()->startOfWeek(),
        };
    }
}
