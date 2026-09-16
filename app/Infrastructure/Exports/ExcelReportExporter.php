<?php

namespace App\Infrastructure\Exports;

use App\Application\Contracts\Export\ReportExporter;
use App\Exports\Reports\DailyHistogramExport;
use App\Models\Report;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

final class ExcelReportExporter implements ReportExporter
{
    public function export(Report $report, array $data): string
    {
        $export = new DailyHistogramExport(collect($data));

        $fileName = "reports/report_{$report->id}_".now()->timestamp.'.xlsx';

        $directory = dirname(storage_path("app/{$fileName}"));
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        Excel::store($export, $fileName);

        return $fileName;
    }
}
