<?php

namespace App\Exports\Reports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DailyHistogramExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private Collection $data,
    ) {}

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ['Date', 'Post Count'];
    }

    public function map($row): array
    {
        return [
            $row['date'],
            $row['count'],
        ];
    }
}
