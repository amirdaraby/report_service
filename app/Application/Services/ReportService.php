<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\ReportRepository;
use App\Models\Report;

class ReportService
{
    public function __construct(
        private ReportRepository $reportRepository
    ) {}

    public function create(){}

    public function listByUserId($userId) {}
}
