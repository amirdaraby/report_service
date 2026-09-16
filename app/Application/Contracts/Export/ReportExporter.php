<?php

namespace App\Application\Contracts\Export;

use App\Models\Report;

interface ReportExporter
{
    public function export(Report $report, array $data): string;
}
