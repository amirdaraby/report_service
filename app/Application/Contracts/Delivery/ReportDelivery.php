<?php

namespace App\Application\Contracts\Delivery;

use App\Models\Report;

interface ReportDelivery
{
    public function send(Report $report, string $filePath): void;
}
